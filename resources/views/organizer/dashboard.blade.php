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
                    <a href="{{ route('analytics.dashboard') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['totalEvents'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-ticket-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tickets Sold</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalTicketsSold']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['totalRevenue'], 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['upcomingEvents'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Your Events</h2>
                    <a href="{{ route('organizer.events') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recentEvents && $recentEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentEvents->take(5) as $event)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $event->date->format('M d, Y') }} • {{ $event->location }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-ticket-alt mr-1"></i>
                                            {{ $event->bookings->sum('quantity') }}/{{ $event->capacity }} sold
                                        </span>
                                        <span class="text-sm text-green-600 font-medium">
                                            ${{ number_format($event->bookings->sum('final_price'), 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($event->status === 'approved') bg-green-100 text-green-800
                                        @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                    <a href="{{ route('analytics.event', $event) }}" 
                                       class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-plus text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No events yet</h3>
                        <p class="text-gray-600 mb-4">Create your first event to get started</p>
                        <a href="{{ route('organizer.events.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Create Event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection