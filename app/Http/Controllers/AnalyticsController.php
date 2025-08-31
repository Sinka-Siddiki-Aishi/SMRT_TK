<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard($eventId = null)
    {
        $organizer = Auth::user();
        
        if ($eventId) {
            $event = Event::where('organizer_id', $organizer->id)->findOrFail($eventId);
            return $this->eventAnalytics($event);
        }

        $events = Event::where('organizer_id', $organizer->id)->get();
        
        $totalRevenue = $events->sum(function($event) {
            return $event->bookings->sum('final_price');
        });

        $totalTicketsSold = $events->sum(function($event) {
            return $event->bookings->sum('quantity');
        });

        $monthlyRevenue = Booking::whereHas('event', function($query) use ($organizer) {
                                    $query->where('organizer_id', $organizer->id);
                                })
                                ->select(
                                    DB::raw("DATE_FORMAT(created_at, '%m') as month"),
                                    DB::raw('SUM(final_price) as revenue')
                                )
                                ->whereYear('created_at', now()->year)
                                ->groupBy('month')
                                ->get();

        return view('analytics.dashboard', compact('events', 'totalRevenue', 'totalTicketsSold', 'monthlyRevenue'));
    }

    private function eventAnalytics($event)
    {
        $bookings = $event->bookings;
        
        $analytics = [
            'total_revenue' => $bookings->sum('final_price'),
            'tickets_sold' => $bookings->sum('quantity'),
            'conversion_rate' => ($bookings->count() / max($event->views ?? 1, 1)) * 100,
            'ticket_types' => $bookings->groupBy('ticket_type')->map->count(),
            'daily_sales' => $bookings->groupBy(function($booking) {
                return $booking->created_at->format('Y-m-d');
            })->map->sum('quantity')
        ];

        return view('analytics.event', compact('event', 'analytics'));
    }
}