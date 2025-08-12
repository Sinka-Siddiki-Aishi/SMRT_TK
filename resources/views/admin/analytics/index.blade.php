@extends('layouts.app')

@section('title', 'Analytics - Admin')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="mt-2 text-gray-600">Platform performance and insights</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['total_events'] ?? 0 }}</p>
                        <p class="text-xs text-green-600">+{{ $analytics['events_growth'] ?? 0 }}% this month</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['total_users'] ?? 0 }}</p>
                        <p class="text-xs text-green-600">+{{ $analytics['users_growth'] ?? 0 }}% this month</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['total_bookings'] ?? 0 }}</p>
                        <p class="text-xs text-green-600">+{{ $analytics['bookings_growth'] ?? 0 }}% this month</p>
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
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($analytics['total_revenue'] ?? 0, 2) }}</p>
                        <p class="text-xs text-green-600">+{{ $analytics['revenue_growth'] ?? 0 }}% this month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">Revenue chart would be displayed here</p>
                        <p class="text-sm text-gray-400">Integration with Chart.js or similar library needed</p>
                    </div>
                </div>
            </div>

            <!-- User Growth Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">User Growth</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                    <div class="text-center">
                        <i class="fas fa-chart-area text-gray-400 text-4xl mb-2"></i>
                        <p class="text-gray-500">User growth chart would be displayed here</p>
                        <p class="text-sm text-gray-400">Integration with Chart.js or similar library needed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Events and Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Events -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Events</h3>
                @if(isset($analytics['top_events']) && count($analytics['top_events']) > 0)
                    <div class="space-y-4">
                        @foreach($analytics['top_events'] as $event)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $event->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $event->organizer->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">{{ $event->bookings_count ?? 0 }} bookings</p>
                                    <p class="text-sm text-gray-500">${{ number_format($event->total_revenue ?? 0, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">No event data available</p>
                    </div>
                @endif
            </div>

            <!-- Popular Categories -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Categories</h3>
                @if(isset($analytics['popular_categories']) && count($analytics['popular_categories']) > 0)
                    <div class="space-y-4">
                        @foreach($analytics['popular_categories'] as $category)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $category->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">{{ $category->events_count ?? 0 }} events</p>
                                    <p class="text-sm text-gray-500">{{ $category->bookings_count ?? 0 }} bookings</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-tags text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">No category data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                @if(isset($analytics['recent_activities']) && count($analytics['recent_activities']) > 0)
                    <div class="space-y-4">
                        @foreach($analytics['recent_activities'] as $activity)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-{{ $activity['icon'] ?? 'info' }} text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $activity['message'] ?? 'Activity message' }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] ?? 'Time' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clock text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
