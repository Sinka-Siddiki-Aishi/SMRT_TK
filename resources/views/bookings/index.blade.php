@extends('layouts.app')

@section('title', 'My Bookings - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
                    <p class="mt-2 text-gray-600">Manage your event tickets and booking history</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('events.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Book New Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($bookings->count() > 0)
            <!-- Booking Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookings->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Confirmed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Upcoming</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $bookings->filter(function($booking) { return $booking->event->date > now(); })->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Spent</p>
                            <p class="text-2xl font-bold text-gray-900">${{ number_format($bookings->where('status', 'confirmed')->sum('final_price'), 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button class="tab-button active py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-filter="all">
                            All Bookings
                        </button>
                        <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-filter="upcoming">
                            Upcoming
                        </button>
                        <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-filter="past">
                            Past Events
                        </button>
                        <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700" data-filter="cancelled">
                            Cancelled
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="space-y-6">
                @foreach($bookings as $booking)
                <div class="booking-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow" 
                     data-status="{{ $booking->status }}" 
                     data-date="{{ $booking->event->date->isPast() ? 'past' : 'upcoming' }}">
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <!-- Event Info -->
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Date Badge -->
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                    <div class="text-center">
                                        <div class="text-lg font-bold">{{ $booking->event->date->format('d') }}</div>
                                        <div class="text-xs">{{ $booking->event->date->format('M') }}</div>
                                    </div>
                                </div>
                                
                                <!-- Event Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900 truncate">
                                            <a href="{{ route('events.show', $booking->event) }}" class="hover:text-blue-600">
                                                {{ $booking->event->title }}
                                            </a>
                                        </h3>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ $booking->event->date->format('l, F j, Y \a\t g:i A') }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            {{ $booking->event->venue }}, {{ $booking->event->city }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-ticket-alt mr-2"></i>
                                            {{ $booking->quantity }} {{ ucfirst($booking->ticket_type) }} {{ Str::plural('ticket', $booking->quantity) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Booking Info & Actions -->
                            <div class="text-right ml-4">
                                <div class="mb-4">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($booking->final_price, 2) }}</div>
                                    <div class="text-sm text-gray-500">Booking #{{ $booking->booking_number }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->created_at->format('M d, Y') }}</div>
                                </div>
                                
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                        View Details
                                    </a>
                                    
                                    @if($booking->status === 'confirmed')
                                        <a href="{{ route('bookings.pdf', $booking) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                            <i class="fas fa-download mr-1"></i>PDF
                                        </a>
                                        
                                        @if($booking->canBeCancelled())
                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to cancel this booking?')" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rating Section (for past events) -->
                        @if($booking->event->date->isPast() && $booking->status === 'confirmed')
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    How was this event? Share your experience:
                                </div>
                                <a href="{{ route('events.show', $booking->event) }}#ratings" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-star mr-1"></i>Rate Event
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings yet</h3>
                <p class="text-gray-600 mb-6">Start exploring amazing events and book your first ticket!</p>
                <a href="{{ route('events.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-search mr-2"></i>Discover Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const bookingCards = document.querySelectorAll('.booking-card');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active tab
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.add('active', 'border-blue-500', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Filter bookings
            bookingCards.forEach(card => {
                const status = card.dataset.status;
                const date = card.dataset.date;
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'upcoming':
                        show = date === 'upcoming' && status === 'confirmed';
                        break;
                    case 'past':
                        show = date === 'past';
                        break;
                    case 'cancelled':
                        show = status === 'cancelled';
                        break;
                }
                
                if (show) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
