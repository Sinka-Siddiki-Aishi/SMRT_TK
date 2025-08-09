<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Admin Dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'pending_events' => Event::where('status', 'pending')->count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('final_price'),
            'organizers' => User::where('role', 'organizer')->count(),
        ];

        $recentEvents = Event::with(['organizer', 'category'])
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
                          ->limit(10)
                          ->get();

        $monthlyRevenue = Booking::where('status', 'confirmed')
                                ->where('created_at', '>=', now()->subMonths(12))
                                ->selectRaw('MONTH(created_at) as month, SUM(final_price) as revenue')
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentEvents', 'recentUsers', 'monthlyRevenue'));
    }

    // Event Management
    public function events(Request $request)
    {
        $query = Event::with(['organizer', 'category']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    // Approve event
    public function approveEvent(Event $event)
    {
        $event->update(['status' => 'approved']);

        return back()->with('success', 'Event approved successfully.');
    }

    // Reject event
    public function rejectEvent(Request $request, Event $event)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return back()->with('success', 'Event rejected.');
    }

    // Feature/Unfeature event
    public function toggleFeatured(Event $event)
    {
        $event->update(['featured' => !$event->featured]);

        $status = $event->featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Event {$status} successfully.");
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->withCount(['bookings', 'organizedEvents'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    // Change user role
    public function changeUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,organizer,admin'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

    // Suspend/Activate user
    public function toggleUserStatus(User $user)
    {
        $user->update(['active' => !$user->active]);

        $status = $user->active ? 'activated' : 'suspended';
        return back()->with('success', "User {$status} successfully.");
    }

    // Booking Management
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'event']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    // Platform Analytics
    public function analytics()
    {
        $analytics = [
            'events_by_category' => Event::join('categories', 'events.category_id', '=', 'categories.id')
                                        ->selectRaw('categories.name, COUNT(*) as count')
                                        ->groupBy('categories.name')
                                        ->get(),

            'bookings_by_month' => Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                         ->where('created_at', '>=', now()->subMonths(12))
                                         ->groupBy('month')
                                         ->orderBy('month')
                                         ->get(),

            'revenue_by_month' => Booking::selectRaw('MONTH(created_at) as month, SUM(final_price) as revenue')
                                        ->where('status', 'confirmed')
                                        ->where('created_at', '>=', now()->subMonths(12))
                                        ->groupBy('month')
                                        ->orderBy('month')
                                        ->get(),

            'top_events' => Event::withCount('bookings')
                                 ->orderBy('bookings_count', 'desc')
                                 ->limit(10)
                                 ->get(),

            'top_organizers' => User::where('role', 'organizer')
                                   ->withCount('organizedEvents')
                                   ->orderBy('organized_events_count', 'desc')
                                   ->limit(10)
                                   ->get(),
        ];

        return view('admin.analytics', compact('analytics'));
    }

    // Category Management
    public function categories()
    {
        $categories = Category::withCount('events')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        Category::create($request->all());

        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        return back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->events()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing events.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
