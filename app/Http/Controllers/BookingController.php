<?php


namespace App\Http\Controllers;


use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;


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
           'ticket_type' => 'required|in:general,vip,premium',
           'quantity' => 'required|integer|min:1|max:10',
       ]);


       if (!$event->hasAvailableTickets($request->quantity)) {
           return back()->with('error', 'Not enough tickets available.');
       }


       DB::beginTransaction();
       try {
           $ticketType = $request->ticket_type;
           $quantity = $request->quantity;
           $unitPrice = $event->getSmartPricing($ticketType);
           $totalPrice = $unitPrice * $quantity;


           // Apply deals if any
           $discountAmount = 0;
           $activeDeals = $event->deals()->active()->get();
           foreach ($activeDeals as $deal) {
               $dealDiscount = $deal->calculateDiscount($unitPrice, $quantity);
               if ($dealDiscount > $discountAmount) {
                   $discountAmount = $dealDiscount;
                   $appliedDeal = $deal;
               }
           }


           $finalPrice = $totalPrice - $discountAmount;


           // Create booking
           $booking = Booking::create([
               'user_id' => Auth::id(),
               'event_id' => $event->id,
               'quantity' => $quantity,
               'ticket_type' => $ticketType,
               'unit_price' => $unitPrice,
               'total_price' => $totalPrice,
               'discount_amount' => $discountAmount,
               'final_price' => $finalPrice,
               'status' => 'confirmed', // In real app, this would be 'pending' until payment
           ]);


           // Generate tickets with QR codes
           $booking->generateTickets();


           // Update event booking count
           $event->increment('booking_count', $quantity);


           // Update deal usage if applied
           if (isset($appliedDeal)) {
               $appliedDeal->incrementUsage();
           }


           DB::commit();


           return redirect()->route('bookings.show', $booking)
                          ->with('success', 'Booking confirmed! Your tickets have been generated.');


       } catch (\Exception $e) {
           DB::rollback();
           return back()->with('error', 'Booking failed. Please try again.');
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

   // Preview PDF ticket in browser
   public function previewPDF(Booking $booking)
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

       return $pdf->stream('SmartTix-Ticket-' . $booking->booking_number . '.pdf');
   }


   // Verify QR code
   public function verifyQR($qrCode)
   {
       $ticket = Ticket::where('qr_code', $qrCode)->first();


       if (!$ticket) {
           return response()->json(['valid' => false, 'message' => 'Invalid QR code']);
       }


       if (!$ticket->isValid()) {
           return response()->json(['valid' => false, 'message' => 'Ticket is not valid']);
       }


       return response()->json([
           'valid' => true,
           'ticket' => $ticket->load(['event', 'user']),
           'message' => 'Valid ticket'
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





