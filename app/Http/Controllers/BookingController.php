<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
   public function __construct()
   {
       $this->middleware('auth');
   }

   // Show booking form
   public function create(Event $event)
   {
       $pricing = [
           'general' => $event->getSmartPricing('general'),
           'vip' => $event->getSmartPricing('vip'),
           'premium' => $event->getSmartPricing('premium'),
       ];

       $activeDeals = $event->deals()->active()->get();

       return view('bookings.create', compact('event', 'pricing', 'activeDeals'));
   }

   // Process booking
   public function store(Request $request, Event $event)
   {
       $request->validate([
           'ticket_type' => 'required|string',
           'quantity' => 'required|integer|min:1',
           'payment_method' => 'required|string',
       ]);
       $user = Auth::user();

       $event = Event::findOrFail($request->input('event_id'));
       $ticketType = $request->input('ticket_type');
       $quantity = $request->input('quantity');
   
       // Get the price for the specific ticket type
       $unitPrice = $event->getSmartPricing($ticketType);
       $totalPrice = $unitPrice * $quantity;
       $finalPrice = $totalPrice; // Assuming no discount for now
   
       // Check if there are enough tickets available
       if (!$event->hasAvailableTickets($quantity)) {
           return redirect()->back()->with('error', 'Sorry, there are not enough tickets available.');
       }
   
       // Start a database transaction
       DB::beginTransaction();
   
       try {
        // Create the booking with pending status
        $booking = new Booking([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'ticket_type' => $ticketType,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
           'final_price' => $finalPrice,
           'status' => 'pending', // Initial status
            'booking_number' => 'BKG' . strtoupper(Str::random(8)),
        ]);
        $booking->save(); // Save the booking to get an ID

        // Handle payment
        if ($request->payment_method === 'wallet') {
            $withdrawalSuccessful = $user->withdraw($totalPrice, "Payment for booking #{$booking->id}");

            if (!$withdrawalSuccessful) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Insufficient wallet balance. Payment failed.');
            }
        }

        // If payment is successful, update the booking status to 'confirmed'
        $booking->status = 'confirmed';
        $booking->save();

        // Deposit the ticket price into the organizer's wallet
        $organizer = $event->organizer;
        if ($organizer->wallet) {
            $organizer->deposit($finalPrice, "Payment for booking #{$booking->id}");
        }

        // Commit the transaction
        DB::commit();

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking successful!');
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollBack();
        Log::error("Booking failed for user {$user->id} for event {$event->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred during the booking process. Please try again.');
    }
   }

   // Show booking details
   public function show(Booking $booking)
   {
       $this->authorize('view', $booking);

       $booking->load(['event', 'tickets']);

       return view('bookings.show', compact('booking'));
   }

   // User booking history
   public function index()
   {
       $bookings = Auth::user()->bookings()
                             ->with(['event', 'tickets'])
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

       return view('bookings.index', compact('bookings'));
   }

   // Cancel booking
   public function cancel(Booking $booking)
   {
       $this->authorize('cancel', $booking);

       if (!$booking->canBeCancelled()) {
           return back()->with('error', 'This booking cannot be cancelled.');
       }

       DB::beginTransaction();
       try {
           $booking->update(['status' => 'cancelled']);
           $booking->tickets()->update(['status' => 'cancelled']);

           // Decrease event booking count
           $booking->event->decrement('booking_count', $booking->quantity);

           DB::commit();

           return back()->with('success', 'Booking cancelled successfully.');
       } catch (\Exception $e) {
           DB::rollback();
           return back()->with('error', 'Failed to cancel booking.');
       }
   }

   // Download PDF ticket
   public function downloadPDF(Booking $booking)
   {
       $this->authorize('view', $booking);

       $booking->load(['event', 'tickets', 'user']);

       $pdf = Pdf::loadView('bookings.pdf', compact('booking'))
                 ->setPaper('a4', 'portrait')
                 ->setOptions([
                     'dpi' => 150,
                     'defaultFont' => 'sans-serif',
                     'isHtml5ParserEnabled' => true,
                     'isRemoteEnabled' => true,
                 ]);

       return $pdf->download('SmartTix-Ticket-' . $booking->booking_number . '.pdf');
   }

   // Stream PDF ticket in browser
   public function streamPDF(Booking $booking)
   {
       $this->authorize('view', $booking);

       $booking->load(['event', 'tickets', 'user']);

       $pdf = Pdf::loadView('bookings.pdf', compact('booking'))
                 ->setPaper('a4', 'portrait')
                 ->setOptions([
                     'dpi' => 150,
                     'defaultFont' => 'sans-serif',
                     'isHtml5ParserEnabled' => true,
                     'isRemoteEnabled' => true,
                 ]);

       return response($pdf->output(), 200, [
           'Content-Type' => 'application/pdf',
           'Content-Disposition' => 'inline; filename="SmartTix-Ticket-' . $booking->booking_number . '.pdf"',
       ]);
   }

   // Verify QR code
   public function verifyQR($qrCode)
   {
       $ticket = Ticket::with(['event.organizer', 'user'])->where('qr_code', $qrCode)->first();

       if (!$ticket) {
           return view('tickets.verify', [
               'status' => 'invalid',
               'message' => 'This QR code is not associated with any ticket.'
           ]);
       }

       $viewStatus = 'invalid';
       $message = '';

       if ($ticket->status === 'active' && $ticket->event->date > now()) {
           $viewStatus = 'valid';
           $message = 'This is a valid, active ticket.';
       } elseif ($ticket->status === 'used') {
           $viewStatus = 'used';
           $message = 'This ticket has already been used.';
       } elseif ($ticket->status === 'cancelled') {
           $message = 'This ticket has been cancelled.';
       } elseif ($ticket->event->date < now()) {
           $message = 'This ticket is for a past event and is expired.';
       } else {
           $message = 'This ticket is not valid.';
       }

       return view('tickets.verify', [
           'status' => $viewStatus,
           'message' => $message,
           'ticket' => $ticket
       ]);
   }

   // Mark ticket as used
   public function useTicket($qrCode)
   {
       $ticket = Ticket::where('qr_code', $qrCode)->first();

       if (!$ticket || !$ticket->isValid()) {
           return response()->json(['success' => false, 'message' => 'Invalid ticket']);
       }

       $ticket->markAsUsed();

       return response()->json(['success' => true, 'message' => 'Ticket marked as used']);
   }
}