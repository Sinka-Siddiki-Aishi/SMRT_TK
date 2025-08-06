<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Deal;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer', 'deals'])
                     ->approved()
                     ->upcoming();

        // Real-time search functionality
        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('venue', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->input('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter by location/city
        if ($request->has('location') && $request->input('location')) {
            $query->where(function($q) use ($request) {
                $location = $request->input('location');
                $q->where('city', 'like', '%' . $location . '%')
                  ->orWhere('location', 'like', '%' . $location . '%')
                  ->orWhere('venue', 'like', '%' . $location . '%');
            });
        }

        // Filter by date
        if ($request->has('date') && $request->input('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        // Sorting options
        $sort = $request->get('sort', 'date');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('booking_count', 'desc');
                break;
            default:
                $query->orderBy('date', 'asc');
        }

        $events = $query->paginate(12);
        $categories = Category::all();
        $topRatedEvents = Event::approved()->topRated()->limit(6)->get();
        $featuredEvents = Event::approved()->featured()->limit(6)->get();

        return view('events.index', compact('events', 'categories', 'topRatedEvents', 'featuredEvents'));
    }

    public function show($id)
    {
        $event = Event::with(['category', 'organizer', 'ratings.user', 'deals'])
                     ->findOrFail($id);

        // Get smart pricing for different ticket types
        $pricing = [
            'general' => $event->getSmartPricing('general'),
            'vip' => $event->getSmartPricing('vip'),
            'premium' => $event->getSmartPricing('premium'),
        ];

        // Get active deals
        $activeDeals = $event->deals()->active()->get();

        // Get related events
        $relatedEvents = Event::approved()
                             ->where('category_id', $event->category_id)
                             ->where('id', '!=', $event->id)
                             ->limit(3)
                             ->get();

        // Get recent ratings
        $recentRatings = $event->ratings()
                              ->with('user')
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return view('events.show', compact('event', 'pricing', 'activeDeals', 'relatedEvents', 'recentRatings'));
    }

    // AJAX endpoint for real-time search
    public function search(Request $request)
    {
        $search = $request->get('q');

        $events = Event::approved()
                      ->upcoming()
                      ->where(function($query) use ($search) {
                          $query->where('title', 'like', '%' . $search . '%')
                                ->orWhere('location', 'like', '%' . $search . '%')
                                ->orWhere('city', 'like', '%' . $search . '%');
                      })
                      ->limit(10)
                      ->get(['id', 'title', 'location', 'city', 'date', 'price']);

        return response()->json($events);
    }

    // Get top-rated events
    public function topRated()
    {
        $events = Event::approved()
                      ->topRated()
                      ->with(['category', 'organizer'])
                      ->paginate(12);

        return view('events.top-rated', compact('events'));
    }

    // Get featured events
    public function featured()
    {
        $events = Event::approved()
                      ->featured()
                      ->with(['category', 'organizer'])
                      ->paginate(12);

        return view('events.featured', compact('events'));
    }
}