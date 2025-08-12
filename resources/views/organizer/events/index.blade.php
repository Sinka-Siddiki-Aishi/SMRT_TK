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
                    <p class="mt-2 text-gray-600">Manage all your events</p>
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
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Event Image -->
                        <div class="h-48 bg-gray-200 relative">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if($event->status === 'approved')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i>Approved
                                    </span>
                                @elseif($event->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($event->status === 'rejected')
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-times mr-1"></i>Rejected
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                        <i class="fas fa-ban mr-1"></i>Cancelled
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
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $event->date->format('M d, Y') }} at {{ $event->time->format('g:i A') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $event->location }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    ${{ number_format($event->price, 2) }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $event->bookings_count ?? 0 }} / {{ $event->max_attendees }} attendees
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <a href="{{ route('organizer.events.edit', $event) }}" 
                                       class="bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    
                                    @if($event->status !== 'cancelled')
                                        <form method="POST" action="{{ route('organizer.events.cancel', $event) }}" 
                                              onsubmit="return confirm('Are you sure you want to cancel this event?')" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                                <i class="fas fa-ban mr-1"></i>Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <a href="{{ route('events.show', $event) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Details <i class="fas fa-arrow-right ml-1"></i>
                                </a>
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
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-plus text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No events yet</h3>
                <p class="text-gray-600 mb-6">Start by creating your first event to engage with your audience.</p>
                <a href="{{ route('organizer.events.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create Your First Event
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
