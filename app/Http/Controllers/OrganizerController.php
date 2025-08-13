<?php


namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class OrganizerController extends Controller
{
   public function __construct()
   {
       // Middleware is handled in routes
   }


   // Organizer Dashboard
   public function dashboard()
   {
       $organizer = Auth::user();


       $stats = [
           'totalEvents' => $organizer->organizedEvents()->count(),
           'upcomingEvents' => $organizer->organizedEvents()->upcoming()->count(),
           'totalTicketsSold' => Booking::whereHas('event', function($q) use ($organizer) {
               $q->where('organizer_id', $organizer->id);
           })->where('status', 'confirmed')->sum('quantity'),
           'totalRevenue' => Booking::whereHas('event', function($q) use ($organizer) {
               $q->where('organizer_id', $organizer->id);
           })->where('status', 'confirmed')->sum('final_price'),
       ];


       $recentEvents = $organizer->organizedEvents()
                                ->with(['category', 'bookings'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();


       $recentBookings = Booking::whereHas('event', function($q) use ($organizer) {
                                   $q->where('organizer_id', $organizer->id);
                               })
                               ->with(['event', 'user'])
                               ->orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();


       return view('organizer.dashboard', compact('stats', 'recentEvents', 'recentBookings'));
   }


   // List organizer's events
   public function events()
   {
       $events = Auth::user()->organizedEvents()
                            ->with(['category', 'bookings'])
                            ->orderBy('date', 'desc')
                            ->paginate(10);


       return view('organizer.events.index', compact('events'));
   }


   // Show create event form
   public function createEvent()
   {
       $categories = Category::all();
       return view('organizer.events.create', compact('categories'));
   }


   // Store new event
   public function store(Request $request)
   {
       // Debug: Log the request data
       Log::info('Event creation attempt', [
           'user_id' => Auth::id(),
           'request_data' => $request->all()
       ]);


       $request->validate([
           'title' => 'required|string|max:255',
           'description' => 'required|string',
           'date' => 'required|date',
           'time' => 'required',
           'end_date' => 'nullable|date|after:date',
           'location' => 'required|string|max:255',
           'venue' => 'nullable|string|max:255',
           'address' => 'nullable|string',
           'city' => 'required|string|max:255',
           'state' => 'nullable|string|max:255',
           'category_id' => 'required|exists:categories,id',
           'price' => 'required|numeric|min:0',
           'vip_price' => 'nullable|numeric|min:0',
           'premium_price' => 'nullable|numeric|min:0',
           'capacity' => 'required|integer|min:1',

           'performers' => 'nullable|string',
       ]);


       $eventData = $request->all();
       $eventData['organizer_id'] = Auth::id();
       $eventData['available_tickets'] = $request->capacity;
       $eventData['status'] = 'approved'; // Immediately visible to all users

       // Combine date and time
       if ($request->date && $request->time) {
           $eventData['date'] = $request->date . ' ' . $request->time;
       }

       // Remove separate time field since it's combined with date
       unset($eventData['time']);





       // Handle performers (convert to array)
       if ($request->performers) {
           $eventData['performers'] = array_map('trim', explode(',', $request->performers));
       }


       try {
           $event = Event::create($eventData);


           Log::info('Event created successfully', [
               'event_id' => $event->id,
               'event_title' => $event->title,
               'event_status' => $event->status
           ]);


           return redirect()->route('organizer.events')
                           ->with('success', 'Event created successfully! It will be reviewed by admin.');
       } catch (\Exception $e) {
           Log::error('Event creation failed', [
               'error' => $e->getMessage(),
               'trace' => $e->getTraceAsString(),
               'user_id' => Auth::id(),
               'event_data' => $eventData
           ]);


           return back()->withInput()
                       ->with('error', 'Failed to create event: ' . $e->getMessage());
       }
   }

   // Alias for backward compatibility
   public function storeEvent(Request $request)
   {
       return $this->store($request);
   }

   // Alias for backward compatibility
   public function deleteEvent(Event $event)
   {
       return $this->destroy($event);
   }

   // Alias for backward compatibility
   public function edit(Event $event)
   {
       return $this->editEvent($event);
   }

   // Alias for backward compatibility
   public function update(Request $request, Event $event)
   {
       return $this->updateEvent($request, $event);
   }


   // Show edit event form
   public function editEvent(Event $event)
   {
       $this->authorize('update', $event);


       $categories = Category::all();
       return view('organizer.events.edit', compact('event', 'categories'));
   }


   // Update event
   public function updateEvent(Request $request, Event $event)
   {
       // Debug: Check if method is called
       Log::info('Update method called', [
           'event_id' => $event->id,
           'user_id' => Auth::id(),
           'request_data' => $request->all()
       ]);

       $this->authorize('update', $event);


       $request->validate([
           'title' => 'required|string|max:255',
           'description' => 'required|string',
           'date' => 'required|date',
           'time' => 'required',
           'end_date' => 'nullable|date|after:date',
           'location' => 'required|string|max:255',
           'venue' => 'nullable|string|max:255',
           'address' => 'nullable|string',
           'city' => 'required|string|max:255',
           'state' => 'nullable|string|max:255',
           'category_id' => 'required|exists:categories,id',
           'price' => 'required|numeric|min:0',
           'vip_price' => 'nullable|numeric|min:0',
           'premium_price' => 'nullable|numeric|min:0',
           'capacity' => 'required|integer|min:1',

           'performers' => 'nullable|string',
       ]);


       $eventData = $request->all();

       // Combine date and time
       if ($request->date && $request->time) {
           $eventData['date'] = $request->date . ' ' . $request->time;
       }

       // Remove separate time field since it's combined with date
       unset($eventData['time']);





       // Handle performers
       if ($request->performers) {
           $eventData['performers'] = array_map('trim', explode(',', $request->performers));
       }


       // Debug: Log before update
       Log::info('About to update event', [
           'event_id' => $event->id,
           'old_title' => $event->title,
           'new_data' => $eventData
       ]);

       $event->update($eventData);

       // Debug: Log after update
       Log::info('Event updated successfully', [
           'event_id' => $event->id,
           'new_title' => $event->fresh()->title
       ]);

       return redirect()->route('organizer.events')
                       ->with('success', 'Event updated successfully!');
   }





   // Delete event
   public function destroy(Event $event)
   {
       $this->authorize('delete', $event);


       if ($event->bookings()->where('status', 'confirmed')->exists()) {
           return back()->with('error', 'Cannot delete event with confirmed bookings.');
       }





       $event->delete();


       return redirect()->route('organizer.events')
                       ->with('success', 'Event deleted successfully.');
   }


   // View event bookings
   public function eventBookings(Event $event)
   {
       // Ensure the event belongs to the authenticated organizer
       if ($event->organizer_id !== Auth::id()) {
           abort(403, 'Unauthorized access to event bookings.');
       }


       $bookings = $event->bookings()
                         ->with(['user', 'tickets'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);


       return view('organizer.events.bookings', compact('event', 'bookings'));
   }
}



