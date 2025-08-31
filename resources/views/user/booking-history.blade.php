@extends('layouts.app')

@section('title', 'Booking History - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Booking History</h1>
                    <p class="mt-2 text-gray-600">View all your past and current bookings</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('events.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>Browse Events
                    </a>
                    <a href="{{ route('user.dashboard') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($bookings->count() > 0)
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <!-- Event Image -->
                                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($booking->event->image)
                                                <img src="{{ asset('storage/' . $booking->event->image) }}" 
                                                     alt="{{ $booking->event->title }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Event Details -->
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $booking->event->title }}</h3>
                                            <div class="space-y-1 text-sm text-gray-600">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                                    {{ $booking->event->date->format('M d, Y g:i A') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                                    {{ $booking->event->location }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-ticket-alt mr-2 text-green-500"></i>
                                                    {{ $booking->quantity }} {{ Str::plural('ticket', $booking->quantity) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Booking Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Booking ID</div>
                                            <div class="text-sm font-medium text-gray-900">#{{ $booking->booking_number }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Booked On</div>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('M d, Y') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 uppercase tracking-wide">Total Paid</div>
                                            <div class="text-sm font-medium text-gray-900">৳{{ number_format($booking->final_price, 2) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Actions -->
                                <div class="ml-6 flex flex-col items-end space-y-3">
                                    <!-- Status Badge -->
                                    <div>
                                        @if($booking->status === 'confirmed')
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Confirmed
                                            </span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>Cancelled
                                            </span>
                                        @elseif($booking->status === 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                            <i class="fas fa-eye mr-1"></i>View Details
                                        </a>
                                        
                                        @if($booking->status === 'confirmed')
                                            <a href="{{ route('bookings.preview', $booking) }}" target="_blank"
                                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                                <i class="fas fa-eye mr-1"></i>Preview PDF
                                            </a>

                                            <a href="{{ route('bookings.pdf', $booking) }}"
                                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                                <i class="fas fa-download mr-1"></i>Download PDF
                                            </a>
                                            
                                            @if($booking->event->date > now())
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')" 
                                                      class="w-full">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                        <i class="fas fa-times mr-1"></i>Cancel Booking
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-ticket-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">No Bookings Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    You haven't made any bookings yet. Start exploring events and book your first ticket!
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