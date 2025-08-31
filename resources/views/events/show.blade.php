
@extends('layouts.app')

@section('title', $event->title . ' - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Event Info -->
                <div class="text-white">
                    <!-- Breadcrumb -->
                    <nav class="mb-6">
                        <ol class="flex items-center space-x-2 text-blue-200">
                            <li><a href="{{ url('/') }}" class="hover:text-white">Home</a></li>
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                            <li><a href="{{ route('events.index') }}" class="hover:text-white">Events</a></li>
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                            <li class="text-white">{{ $event->title }}</li>
                        </ol>
                    </nav>

                    <!-- Category Badge -->
                    @if($event->category)
                    <div class="mb-4">
                        <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    @endif

                    <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $event->title }}</h1>

                    <!-- Quick Info -->
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-3 text-blue-200"></i>
                            <span class="text-lg">{{ \Carbon\Carbon::parse($event->date)->format('l, F j, Y \a\t g:i A') }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-blue-200"></i>
                            <span class="text-lg">{{ $event->location }}</span>
                        </div>

                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 text-blue-200"></i>
                            <span class="text-lg">{{ \Carbon\Carbon::parse($event->date)->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Booking Card -->
                <div class="bg-white rounded-xl shadow-2xl p-8">
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-gray-900 mb-2">From ${{ rand(25, 150) }}</div>
                        <p class="text-gray-600">per ticket</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">General Admission</span>
                            <span class="font-semibold">${{ rand(25, 75) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600">VIP Access</span>
                            <span class="font-semibold">${{ rand(100, 150) }}</span>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('bookings.create', $event) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-lg font-semibold text-lg transition-colors mb-4 text-center">
                            <i class="fas fa-ticket-alt mr-2"></i>Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-lg font-semibold text-lg transition-colors mb-4 text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login to Book
                        </a>
                    @endauth

                    <div class="flex items-center justify-center text-sm text-gray-500">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Secure booking with instant confirmation
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
                <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Rate & Review This Event</h2>
                    @auth
                        @php
                            $userBooking = $event->bookings()
                                ->where('user_id', Auth::id())
                                ->where('status', 'confirmed')
                                ->first();
                            $eventEnded = $event->date < now();
                        @endphp

                        @if($userBooking && $eventEnded)
                            @php
                                $userRating = $event->ratings()->where('user_id', Auth::id())->first();
                            @endphp

                            @if($userRating)
                                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                                    <p class="font-bold">You have already rated this event:</p>
                                    <div class="flex items-center mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $userRating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">({{ $userRating->rating }} out of 5)</span>
                                    </div>
                                    <p class="mt-2 text-gray-800">"{{ $userRating->review }}"</p>
                                </div>
                            @else
                                <form action="{{ route('ratings.store', $event) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                        <div class="flex items-center">
                                            <input type="range" id="rating" name="rating" min="1" max="5" class="w-full" oninput="updateRatingStars(this.value)">
                                            <div id="rating-stars" class="flex ml-4">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-gray-300"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="review" class="block text-sm font-medium text-gray-700">Your Review</label>
                                        <textarea name="review" id="review" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                        Submit Review
                                    </button>
                                </form>
                            @endif
                        @elseif($userBooking)
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg">
                                <p>You can rate this event after it has ended.</p>
                            </div>
                        @else
                            <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 rounded-lg">
                                <p>You must book this event to leave a review.</p>
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 rounded-lg">
                            <p><a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Log in</a> to rate this event.</p>
                        </div>
                    @endauth
                </div>

                <!-- Display existing ratings -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">What Others Are Saying</h2>
                    @forelse($event->ratings as $rating)
                        <div class="border-b border-gray-200 py-4">
                            <div class="flex items-center mb-2">
                                <div class="font-bold mr-2">{{ $rating->user->name }}</div>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-600">"{{ $rating->review }}"</p>
                        </div>
                    @empty
                        <p class="text-gray-600">No reviews yet. Be the first to share your thoughts!</p>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Event Info Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-alt text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Date & Time</div>
                                <div class="text-gray-600 text-sm">{{ \Carbon\Carbon::parse($event->date)->format('l, F j, Y') }}</div>
                                <div class="text-gray-600 text-sm">{{ \Carbon\Carbon::parse($event->date)->format('g:i A') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Location</div>
                                <div class="text-gray-600 text-sm">{{ $event->location }}</div>
                            </div>
                        </div>

                        @if($event->category)
                        <div class="flex items-start">
                            <i class="fas fa-tag text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Category</div>
                                <div class="text-gray-600 text-sm">{{ $event->category->name }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Share Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share This Event</h3>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center text-white hover:bg-pink-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center text-white hover:bg-blue-800 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <p class="text-gray-600 text-sm mb-4">Have questions about this event? Our support team is here to help.</p>
                    <a href="#" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-900 py-2 px-4 rounded-lg text-center font-medium transition-colors">
                        <i class="fas fa-envelope mr-2"></i>Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection