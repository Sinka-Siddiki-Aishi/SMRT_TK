@extends('layouts.app')

@section('title', 'Admin Dashboard - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="mt-2 text-gray-600">Platform overview and management tools</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.events') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-cog mr-2"></i>Manage Platform
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_events']) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_events']) }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                        <i class="fas fa-user-tie text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Organizers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['organizers']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Events -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Recent Events</h2>
                        <a href="{{ route('admin.events') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                            Manage All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentEvents as $event)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <div class="text-sm font-bold">{{ $event->date->format('d') }}</div>
                                        <div class="text-xs">{{ $event->date->format('M') }}</div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $event->title }}</h3>
                                    <div class="text-sm text-gray-600">
                                        by {{ $event->organizer->name }} • {{ $event->category->name }}
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($event->status === 'approved') bg-green-100 text-green-800
                                        @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($event->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($event->status === 'pending')
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Approve
                                    </button>
                                </form>
                                <button onclick="showRejectModal({{ $event->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    Reject
                                </button>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Recent Users</h2>
                        <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                            Manage All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">
                                        Joined {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'organizer') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Monthly Revenue</h2>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($monthlyRevenue as $month)
                <div class="flex flex-col items-center">
                    <div class="bg-blue-500 rounded-t" style="height: {{ ($month->revenue / $monthlyRevenue->max('revenue')) * 200 }}px; width: 40px;"></div>
                    <div class="text-xs text-gray-600 mt-2">{{ date('M', mktime(0, 0, 0, $month->month, 1)) }}</div>
                    <div class="text-xs font-semibold text-gray-900">${{ number_format($month->revenue, 0) }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Admin Actions</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.events') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-200">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Manage Events</h3>
                        <p class="text-sm text-gray-600">Approve, reject, and feature events</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors group">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-200">
                        <i class="fas fa-users-cog text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-green-600">Manage Users</h3>
                        <p class="text-sm text-gray-600">User roles and account management</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.analytics') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors group">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-200">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600">Analytics</h3>
                        <p class="text-sm text-gray-600">Platform performance and insights</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.categories') }}" 
                   class="flex items-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors group">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-orange-200">
                        <i class="fas fa-tags text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-orange-600">Categories</h3>
                        <p class="text-sm text-gray-600">Manage event categories</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Event Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 max-w-md mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Event</h3>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection:</label>
                <textarea name="reason" id="reason" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Please provide a reason for rejecting this event..."></textarea>
            </div>
            <div class="flex space-x-4">
                <button type="button" onclick="closeRejectModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Reject Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal(eventId) {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    form.action = `/admin/events/${eventId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
    document.getElementById('reason').value = '';
}

// Close modal when clicking outside
document.getElementById('reject-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush
