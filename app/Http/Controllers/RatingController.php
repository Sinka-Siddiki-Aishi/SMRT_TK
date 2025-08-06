<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Store rating and review
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        // Check if user has attended the event
        $hasAttended = Booking::where('user_id', Auth::id())
                             ->where('event_id', $event->id)
                             ->where('status', 'confirmed')
                             ->exists();

        // Check if user already rated this event
        $existingRating = Rating::where('user_id', Auth::id())
                               ->where('event_id', $event->id)
                               ->first();

        if ($existingRating) {
            return back()->with('error', 'You have already rated this event.');
        }

        Rating::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'rating' => $request->rating,
            'review' => $request->review,
            'verified_purchase' => $hasAttended,
        ]);

        return back()->with('success', 'Thank you for your rating and review!');
    }

    // Update rating
    public function update(Request $request, Rating $rating)
    {
        $this->authorize('update', $rating);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Rating updated successfully!');
    }

    // Delete rating
    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);

        $rating->delete();

        return back()->with('success', 'Rating deleted successfully!');
    }

    // Show all ratings for an event
    public function eventRatings(Event $event)
    {
        $ratings = $event->ratings()
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('events.ratings', compact('event', 'ratings'));
    }
}
