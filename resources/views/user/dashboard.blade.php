@extends('layouts.app')

@section('title', 'My Dashboard - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mt-2 text-gray-600">Manage your bookings and discover new events</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('events.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>Browse Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $upcomingBookings->count() }}</div>
                        <div class="text-sm text-gray-600">Upcoming Events</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $pastBookings->count() }}</div>
                        <div class="text-sm text-gray-600">Past Events</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold text-gray-900">{{ $upcomingBookings->count() + $pastBookings->count() }}</div>
                        <div class="text-sm text-gray-600">Total Bookings</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('events.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-search text-blue-500 text-xl mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-900">Browse Events</div>
                        <div class="text-sm text-gray-600">Find new events</div>
                    </div>
                </a>

                <a href="{{ route('bookings.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-ticket-alt text-green-500 text-xl mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-900">My Bookings</div>
                        <div class="text-sm text-gray-600">View current tickets</div>
                    </div>
                </a>

                <a href="{{ route('user.booking-history') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-history text-purple-500 text-xl mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-900">Booking History</div>
                        <div class="text-sm text-gray-600">View all bookings</div>
                    </div>
                </a>

                <a href="{{ route('profile') }}"
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-user text-gray-500 text-xl mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-900">Profile Settings</div>
                        <div class="text-sm text-gray-600">Update your info</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Upcoming Events -->
        @if($upcomingBookings->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Upcoming Events</h2>
                    <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($upcomingBookings->take(3) as $booking)
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                @if($booking->event->image)
                                    <img src="{{ asset('storage/' . $booking->event->image) }}" 
                                         alt="{{ $booking->event->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $booking->event->title }}</h3>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $booking->event->date->format('M d, Y') }}
                                    @if($booking->event->time)
                                        at {{ $booking->event->time->format('g:i A') }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $booking->event->location }}
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->quantity }} tickets</div>
                                <div class="text-sm text-gray-600">৳{{ number_format($booking->final_price, 2) }}</div>
                            </div>

                            <div class="ml-4">
                                <a href="{{ route('bookings.show', $booking) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        @if($pastBookings->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
                    <a href="{{ route('user.booking-history') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($pastBookings->take(3) as $booking)
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                @if($booking->event->image)
                                    <img src="{{ asset('storage/' . $booking->event->image) }}" 
                                         alt="{{ $booking->event->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $booking->event->title }}</h3>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $booking->event->date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Attended
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->quantity }} tickets</div>
                                <div class="text-sm text-gray-600">৳{{ number_format($booking->final_price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if($upcomingBookings->count() === 0 && $pastBookings->count() === 0)
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">No Bookings Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Start exploring amazing events and book your first ticket to get started!
                </p>
                <a href="{{ route('events.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Browse Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection