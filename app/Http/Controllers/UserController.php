<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Wallet;
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
        \Log::info('User ' . $user->id . ' wallet balance: ' . $user->wallet->balance);
        
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

    public function wallet()
    {
        $user = auth()->user();
        if (!$user->wallet) {
            $user->wallet()->create(['balance' => 0]);
            $user->load('wallet'); // Refresh the user model to load the new wallet
        }
        return view('user.wallet');
    }

    public function recharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $user->wallet->balance += $request->amount;
        $user->wallet->save();

        return redirect()->route('user.wallet')->with('success', 'Wallet recharged successfully!');
    }
}