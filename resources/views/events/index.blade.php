
@extends('layouts.app')

@section('title', 'All Events - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">All Events</h1>
                    <p class="mt-2 text-gray-600">Discover amazing events happening near you</p>
                </div>

                <!-- Search and Filter -->
                <div class="mt-6 md:mt-0 flex flex-col sm:flex-row gap-4">
                    <form method="GET" class="flex flex-col lg:flex-row gap-4" id="search-form">
                        <!-- Real-time Search -->
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search events, venues, cities..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   id="search-input">
                            <!-- Real-time search results -->
                            <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden z-10"></div>
                        </div>

                        <!-- Category Filter -->
                        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Location Filter -->
                        <input type="text" name="location" value="{{ request('location') }}"
                               placeholder="City or venue..."
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                        <!-- Date Filter -->
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                        <!-- Sort Options -->
                        <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Sort by Date</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors whitespace-nowrap">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Top-Rated Events Section -->
    @if($topRatedEvents->count() > 0 && !request()->has('search'))
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">⭐ Top-Rated Events</h2>
                    <p class="text-gray-600">Highly rated by our community</p>
                </div>
                <a href="{{ route('events.top-rated') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topRatedEvents as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                        <div class="relative h-40 bg-gradient-to-r from-yellow-500 to-orange-500">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                                    <i class="fas fa-star mr-1"></i>{{ number_format($event->rating, 1) }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                <div class="text-xs">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h3>
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $event->city }}, {{ $event->state }}
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-blue-600 font-semibold">From ${{ $event->price }}</div>
                                <div class="text-sm text-gray-500">{{ $event->rating_count }} reviews</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Events Section -->
    @if($featuredEvents->count() > 0 && !request()->has('search'))
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">🌟 Featured Events</h2>
                    <p class="text-gray-600">Hand-picked events you shouldn't miss</p>
                </div>
                <a href="{{ route('events.featured') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredEvents as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                        <div class="relative h-40 bg-gradient-to-r from-blue-500 to-purple-600">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black px-3 py-1 rounded-full text-sm font-semibold">
                                    Featured
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                <div class="text-xs">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h3>
                            <div class="flex items-center text-gray-500 text-sm mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $event->city }}, {{ $event->state }}
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-blue-600 font-semibold">From ${{ $event->price }}</div>
                                @if($event->rating > 0)
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    {{ number_format($event->rating, 1) }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- All Events Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                        <!-- Event Image/Header -->
                        <div class="relative h-48 bg-gradient-to-r
                            @switch($event->category->name ?? 'default')
                                @case('Music')
                                    from-purple-500 to-pink-500
                                    @break
                                @case('Sports')
                                    from-blue-500 to-cyan-500
                                    @break
                                @case('Theater')
                                    from-green-500 to-emerald-500
                                    @break
                                @case('Conference')
                                    from-orange-500 to-red-500
                                    @break
                                @default
                                    from-gray-500 to-gray-600
                            @endswitch
                        ">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>

                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 bg-white rounded-lg p-3 text-center shadow-md">
                                <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                <div class="text-xs text-gray-600 uppercase">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2">
                                @if($event->featured)
                                    <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black px-3 py-1 rounded-full text-sm font-semibold">
                                        Featured
                                    </span>
                                @endif
                                @if($event->category)
                                    <span class="bg-white bg-opacity-90 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $event->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Rating Badge -->
                            @if($event->rating > 0)
                            <div class="absolute bottom-4 right-4">
                                <span class="bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-sm flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    {{ number_format($event->rating, 1) }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h3>

                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($event->date)->format('M d, Y \a\t g:i A') }}
                                </div>

                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $event->venue ?? $event->location }}, {{ $event->city }}
                                </div>

                                @if($event->organizer)
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="fas fa-user mr-2"></i>
                                    by {{ $event->organizer->name }}
                                </div>
                                @endif
                            </div>

                            <!-- Availability & Pricing -->
                            <div class="mb-4">
                                @if($event->available_tickets > 0)
                                    <div class="flex items-center text-green-600 text-sm mb-2">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ $event->available_tickets }} tickets available
                                    </div>
                                @else
                                    <div class="flex items-center text-red-600 text-sm mb-2">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Sold out
                                    </div>
                                @endif

                                <!-- Smart Pricing Display -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-blue-600 font-semibold text-lg">
                                            From ${{ $event->getSmartPricing('general') }}
                                        </div>
                                        @if($event->price != $event->getSmartPricing('general'))
                                            <div class="text-gray-500 text-sm line-through">
                                                ${{ $event->price }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        @if($event->rating_count > 0)
                                            <div class="text-sm text-gray-500">
                                                {{ $event->rating_count }} {{ Str::plural('review', $event->rating_count) }}
                                            </div>
                                        @endif
                                        @if($event->booking_count > 0)
                                            <div class="text-sm text-gray-500">
                                                {{ $event->booking_count }} booked
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $event->id) }}"
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                    View Details
                                </a>
                                @if($event->available_tickets > 0)
                                    @auth
                                        <a href="{{ route('bookings.create', $event) }}"
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            <i class="fas fa-ticket-alt"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            <i class="fas fa-ticket-alt"></i>
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination would go here if implemented -->
            <div class="mt-12 text-center">
                <p class="text-gray-600">Showing {{ $events->count() }} events</p>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all categories</p>
                <a href="{{ route('events.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    View All Events
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Real-time search functionality
let searchTimeout;
const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('events.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }, 300);
    });
}

function displaySearchResults(events) {
    if (events.length === 0) {
        searchResults.innerHTML = '<div class="p-4 text-gray-500">No events found</div>';
    } else {
        searchResults.innerHTML = events.map(event => `
            <a href="/events/${event.id}" class="block p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                <div class="font-medium text-gray-900">${event.title}</div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-1"></i>${event.city}
                    <span class="mx-2">•</span>
                    <i class="fas fa-calendar mr-1"></i>${new Date(event.date).toLocaleDateString()}
                    <span class="mx-2">•</span>
                    <span class="text-blue-600 font-medium">$${event.price}</span>
                </div>
            </a>
        `).join('');
    }

    searchResults.classList.remove('hidden');
}

// Hide search results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});

// Accessibility: Text-to-Speech functionality
function speakText(text) {
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 0.8;
        utterance.pitch = 1;
        speechSynthesis.speak(utterance);
    }
}

// Add text-to-speech buttons to event cards
document.addEventListener('DOMContentLoaded', function() {
    const eventCards = document.querySelectorAll('.group');

    eventCards.forEach(card => {
        // Add accessibility button
        const accessibilityBtn = document.createElement('button');
        accessibilityBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
        accessibilityBtn.className = 'absolute top-2 left-2 bg-black bg-opacity-50 text-white p-2 rounded-full text-sm hover:bg-opacity-70 transition-opacity';
        accessibilityBtn.title = 'Read event details aloud';
        accessibilityBtn.setAttribute('aria-label', 'Read event details aloud');

        accessibilityBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const title = card.querySelector('h3 a').textContent;
            const description = card.querySelector('p').textContent;
            const date = card.querySelector('.fa-calendar-alt').parentElement.textContent.trim();
            const location = card.querySelector('.fa-map-marker-alt').parentElement.textContent.trim();

            const textToSpeak = `Event: ${title}. ${description}. ${date}. ${location}`;
            speakText(textToSpeak);
        });

        const imageContainer = card.querySelector('.relative.h-48');
        if (imageContainer) {
            imageContainer.appendChild(accessibilityBtn);
        }
    });
});

// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('search-form');
    const selects = form.querySelectorAll('select');
    const dateInput = form.querySelector('input[type="date"]');

    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });

    if (dateInput) {
        dateInput.addEventListener('change', function() {
            form.submit();
        });
    }
});
</script>
@endpush