<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isOrganizer() && !Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Organizer role required.');
            }
            return $next($request);
        });
    }

    // Organizer Dashboard
    public function dashboard()
    {
        $organizer = Auth::user();

        $stats = [
            'total_events' => $organizer->organizedEvents()->count(),
            'upcoming_events' => $organizer->organizedEvents()->upcoming()->count(),
            'total_bookings' => Booking::whereHas('event', function($q) use ($organizer) {
                $q->where('organizer_id', $organizer->id);
            })->count(),
            'total_revenue' => Booking::whereHas('event', function($q) use ($organizer) {
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
    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'performers' => 'nullable|string',
        ]);

        $eventData = $request->all();
        $eventData['organizer_id'] = Auth::id();
        $eventData['available_tickets'] = $request->capacity;
        $eventData['status'] = 'pending'; // Requires admin approval

        // Handle image upload
        if ($request->hasFile('image')) {
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle performers (convert to array)
        if ($request->performers) {
            $eventData['performers'] = array_map('trim', explode(',', $request->performers));
        }

        $event = Event::create($eventData);

        return redirect()->route('organizer.events')
                        ->with('success', 'Event created successfully! It will be reviewed by admin.');
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
        $this->authorize('update', $event);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'performers' => 'nullable|string',
        ]);

        $eventData = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $eventData['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle performers
        if ($request->performers) {
            $eventData['performers'] = array_map('trim', explode(',', $request->performers));
        }

        $event->update($eventData);

        return redirect()->route('organizer.events')
                        ->with('success', 'Event updated successfully!');
    }

    // Cancel event
    public function cancelEvent(Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->bookings()->where('status', 'confirmed')->exists()) {
            return back()->with('error', 'Cannot cancel event with confirmed bookings.');
        }

        $event->update(['status' => 'cancelled']);

        return back()->with('success', 'Event cancelled successfully.');
    }
}
