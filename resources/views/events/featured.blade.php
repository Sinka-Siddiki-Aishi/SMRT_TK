@extends('layouts.app')

@section('title', 'Featured Events - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Featured Events</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Discover the most popular and trending events handpicked by our team
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($events->count() > 0)
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
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
                            
                            <!-- Featured Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            </div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-white bg-opacity-90 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $event->category->name }}
                                </span>
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
                                    <i class="fas fa-user mr-2 text-green-500"></i>
                                    {{ $event->organizer->name }}
                                </div>
                            </div>

                            <!-- Price and Rating -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($event->early_bird_price && $event->early_bird_price < $event->price)
                                        <span class="text-lg font-bold text-green-600">${{ number_format($event->early_bird_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through ml-1">${{ number_format($event->price, 2) }}</span>
                                        <span class="text-xs text-green-600 ml-1">Early Bird</span>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($event->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @if($event->rating > 0)
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $event->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600 ml-1">({{ $event->rating_count }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <a href="{{ route('events.show', $event) }}" 
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                    View Details
                                </a>
                                @auth
                                    <a href="{{ route('bookings.create', $event) }}" 
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                        Book Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors">
                                        Book Now
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-star text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">No Featured Events Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    We're working on featuring the best events for you. Check back soon or browse all events.
                </p>
                <a href="{{ route('events.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Browse All Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
