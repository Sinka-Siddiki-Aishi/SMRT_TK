<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function getRecommendations()
    {
        if (!Auth::check()) {
            return $this->getPopularEvents();
        }

        $user = Auth::user();
        $userBookings = Booking::where('user_id', $user->id)->with('event')->get();

        if ($userBookings->isEmpty()) {
            return $this->getPopularEvents();
        }

        // Get user's preferred categories
        $preferredCategories = $userBookings->pluck('event.category_id')->unique();
        
        // Get recommended events based on user's booking history
        $recommendations = Event::where('status', 'approved')
                                ->where('date', '>', now())
                                ->whereIn('category_id', $preferredCategories)
                                ->whereNotIn('id', $userBookings->pluck('event.id'))
                                ->orderBy('rating', 'desc')
                                ->orderBy('booking_count', 'desc')
                                ->limit(6)
                                ->get();

        return view('recommendations.index', compact('recommendations'));
    }

    private function getPopularEvents()
    {
        $popularEvents = Event::where('status', 'approved')
                             ->where('date', '>', now())
                             ->orderBy('rating', 'desc')
                             ->orderBy('booking_count', 'desc')
                             ->limit(6)
                             ->get();

        return view('recommendations.index', compact('popularEvents'));
    }

    public function api()
    {
        if (!Auth::check()) {
            return response()->json([
                'recommendations' => Event::where('status', 'approved')->where('date', '>', now())->orderBy('rating', 'desc')->limit(3)->get()
            ]);
        }

        $user = Auth::user();
        $userCategories = Booking::where('user_id', $user->id)
                                ->join('events', 'bookings.event_id', '=', 'events.id')
                                ->pluck('events.category_id')
                                ->unique();

        $recommendations = Event::where('status', 'approved')
                                ->where('date', '>', now())
                                ->when($userCategories->isNotEmpty(), function($query) use ($userCategories) {
                                    return $query->whereIn('category_id', $userCategories);
                                })
                                ->orderBy('rating', 'desc')
                                ->limit(3)
                                ->get();

        return response()->json(['recommendations' => $recommendations]);
    }
}
