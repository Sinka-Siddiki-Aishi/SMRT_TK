@extends('layouts.app')

@section('title', 'Organizer Dashboard - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Organizer Dashboard</h1>
                    <p class="mt-2 text-gray-600">Welcome back, {{ auth()->user()->name }}! Manage your events and track performance.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('organizer.events.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_events'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Events -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Recent Events</h2>
                        <a href="{{ route('organizer.events') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($recentEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentEvents as $event)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                                        <div class="text-center">
                                            <div class="text-sm font-bold">{{ $event->date->format('d') }}</div>
                                            <div class="text-xs">{{ $event->date->format('M') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600">
                                                {{ $event->title }}
                                            </a>
                                        </h3>
                                        <div class="text-sm text-gray-600">
                                            {{ $event->city }} • {{ $event->category->name }}
                                        </div>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($event->status === 'approved') bg-green-100 text-green-800
                                                @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $event->bookings->count() }} bookings
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">
                                        ${{ number_format($event->bookings->where('status', 'confirmed')->sum('final_price'), 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">Revenue</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-plus text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No events yet</h3>
                            <p class="text-gray-600 mb-4">Create your first event to get started</p>
                            <a href="{{ route('organizer.events.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                Create Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Recent Bookings</h2>
                </div>
                
                <div class="p-6">
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $booking->user->name }}</h4>
                                    <div class="text-sm text-gray-600">
                                        {{ $booking->event->title }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $booking->quantity }} {{ ucfirst($booking->ticket_type) }} tickets
                                        • {{ $booking->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="font-bold text-gray-900">
                                        ${{ number_format($booking->final_price, 2) }}
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-ticket-alt text-gray-400 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No bookings yet</h3>
                            <p class="text-gray-600">Bookings will appear here once people start booking your events</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('organizer.events.create') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-200">
                        <i class="fas fa-plus text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Create New Event</h3>
                        <p class="text-sm text-gray-600">Set up a new event and start selling tickets</p>
                    </div>
                </a>
                
                <a href="{{ route('organizer.events') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors group">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-200">
                        <i class="fas fa-list text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-green-600">Manage Events</h3>
                        <p class="text-sm text-gray-600">View and edit your existing events</p>
                    </div>
                </a>
                
                <a href="#" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors group">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-200">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600">View Analytics</h3>
                        <p class="text-sm text-gray-600">Track your event performance and revenue</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Tips for Organizers -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">💡 Tips for Success</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-lightbulb text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Optimize Your Event Title</h3>
                        <p class="text-sm text-gray-600">Use clear, descriptive titles that include key details like location and date</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-camera text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Add High-Quality Images</h3>
                        <p class="text-sm text-gray-600">Events with images get 3x more bookings than those without</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Set Early Bird Pricing</h3>
                        <p class="text-sm text-gray-600">Offer discounts for early bookings to boost initial sales</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3 mt-1">
                        <i class="fas fa-share text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Promote on Social Media</h3>
                        <p class="text-sm text-gray-600">Share your event link on social platforms to reach more people</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
