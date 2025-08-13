<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $upcomingBookings = Booking::where('user_id', $user->id)
                                  ->whereHas('event', function($query) {
                                      $query->where('date', '>', now());
                                  })
                                  ->with(['event', 'tickets'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        $pastBookings = Booking::where('user_id', $user->id)
                              ->whereHas('event', function($query) {
                                  $query->where('date', '<=', now());
                              })
                              ->with(['event', 'tickets'])
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('user.dashboard', compact('upcomingBookings', 'pastBookings'));
    }

    public function bookingHistory()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->with(['event', 'tickets'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('user.booking-history', compact('bookings'));
    }
}