@extends('layouts.app')

@section('title', 'My Events - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Events</h1>
                    <p class="mt-2 text-gray-600">Manage your events and track their performance</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('organizer.events.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create New Event
                    </a>
                    <a href="{{ route('organizer.dashboard') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Events List -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative h-48">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if($event->status === 'approved')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Approved
                                    </span>
                                @elseif($event->status === 'pending')
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Pending
                                    </span>
                                @elseif($event->status === 'rejected')
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Rejected
                                    </span>
                                @else
                                    <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Cancelled
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    {{ $event->date->format('M d, Y') }}
                                    @if($event->time)
                                        at {{ $event->time->format('g:i A') }}
                                    @endif
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    {{ $event->location }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                    ৳{{ number_format($event->price, 2) }}
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-gray-900">{{ $event->bookings_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-600">Bookings</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-gray-900">{{ $event->capacity }}</div>
                                    <div class="text-xs text-gray-600">Max Capacity</div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('events.show', $event) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="{{ route('organizer.events.edit', $event) }}"
                                   class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                
                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('organizer.events.delete', $event) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone and will remove all associated bookings.')"
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>

                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-plus text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">No Events Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    You haven't created any events yet. Start by creating your first event to engage with your audience.
                </p>
                <a href="{{ route('organizer.events.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create Your First Event
                </a>
            </div>
        @endif
    </div>
</div>
@endsection