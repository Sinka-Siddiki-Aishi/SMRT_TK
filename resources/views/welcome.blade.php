@extends('layouts.app')

@section('title', 'SmartTix - Discover Amazing Events')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0 opacity-10">
        <div class="w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Welcome back to
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">SmartTix</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Ready to discover your next amazing experience? Browse events, book tickets, and create unforgettable memories.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-8">
                <form action="{{ route('events.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" placeholder="Search events, artists, venues..." 
                                   class="w-full pl-12 pr-4 py-4 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none text-lg">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <select name="category" class="px-4 py-4 rounded-lg border-0 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">All Categories</option>
                            <option value="music">Music</option>
                            <option value="sports">Sports</option>
                            <option value="theater">Theater</option>
                            <option value="conference">Conference</option>
                        </select>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-black px-8 py-4 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <!-- Authenticated User Buttons -->
                    <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Browse All Events
                    </a>
                    @if(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-chart-line mr-2"></i>My Dashboard
                        </a>
                    @endif
                @else
                    <!-- Guest User Buttons -->
                    <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Browse Events
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </a>
                    <a href="{{ route('login') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black px-8 py-4 rounded-lg font-semibold transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </a>
                @endauth
            </div>

            @guest
            <!-- Guest User Notice -->
            <div class="mt-6 text-center">
                <p class="text-blue-100 text-lg">
                    <i class="fas fa-info-circle mr-2"></i>
                    Create an account to book events or organize your own!
                </p>
            </div>
            @endguest
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 bg-pink-400 rounded-full opacity-20 animate-pulse delay-1000"></div>
    <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-green-400 rounded-full opacity-20 animate-pulse delay-500"></div>
</section>



<!-- Stats Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-2">
                <div class="text-3xl md:text-4xl font-bold text-blue-600">10K+</div>
                <div class="text-gray-600">Events Listed</div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl md:text-4xl font-bold text-purple-600">50K+</div>
                <div class="text-gray-600">Happy Customers</div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl md:text-4xl font-bold text-indigo-600">100+</div>
                <div class="text-gray-600">Cities Covered</div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl md:text-4xl font-bold text-pink-600">24/7</div>
                <div class="text-gray-600">Customer Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Events Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Events</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Don't miss out on these amazing upcoming events</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Event Card 1 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                <div class="relative h-48 bg-gradient-to-r from-pink-500 to-rose-500">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <div class="text-2xl font-bold">15</div>
                        <div class="text-sm">DEC</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Summer Music Festival</h3>
                    <p class="text-gray-600 mb-4">Experience the best of summer with top artists and amazing vibes</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Central Park, NY
                        </div>
                        <div class="text-blue-600 font-semibold">From ৳45</div>
                    </div>
                </div>
            </div>
            
            <!-- Event Card 2 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                <div class="relative h-48 bg-gradient-to-r from-blue-500 to-cyan-500">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-green-400 text-black px-3 py-1 rounded-full text-sm font-semibold">Popular</span>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <div class="text-2xl font-bold">22</div>
                        <div class="text-sm">DEC</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Tech Conference 2024</h3>
                    <p class="text-gray-600 mb-4">Join industry leaders and innovators for the biggest tech event</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Convention Center, SF
                        </div>
                        <div class="text-blue-600 font-semibold">From ৳120</div>
                    </div>
                </div>
            </div>
            
            <!-- Event Card 3 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                <div class="relative h-48 bg-gradient-to-r from-purple-500 to-indigo-500">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-red-400 text-white px-3 py-1 rounded-full text-sm font-semibold">Hot</span>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white">
                        <div class="text-2xl font-bold">28</div>
                        <div class="text-sm">DEC</div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Broadway Show Night</h3>
                    <p class="text-gray-600 mb-4">An unforgettable evening of world-class theater performances</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Broadway Theater, NYC
                        </div>
                        <div class="text-blue-600 font-semibold">From ৳85</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('events.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors inline-flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>View All Events
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Browse by Category</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Find events that match your interests</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('categories.show', 1) }}" class="group">
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-8 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-music text-4xl text-white mb-4"></i>
                    <h3 class="text-xl font-semibold text-white mb-2">Music</h3>
                    <p class="text-purple-100 text-sm">Concerts & Festivals</p>
                </div>
            </a>

            <a href="{{ route('categories.show', 2) }}" class="group">
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl p-8 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-futbol text-4xl text-white mb-4"></i>
                    <h3 class="text-xl font-semibold text-white mb-2">Sports</h3>
                    <p class="text-blue-100 text-sm">Games & Tournaments</p>
                </div>
            </a>

            <a href="{{ route('categories.show', 3) }}" class="group">
                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl p-8 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-theater-masks text-4xl text-white mb-4"></i>
                    <h3 class="text-xl font-semibold text-white mb-2">Theater</h3>
                    <p class="text-green-100 text-sm">Plays & Shows</p>
                </div>
            </a>

            <a href="{{ route('categories.show', 4) }}" class="group">
                <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl p-8 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-users text-4xl text-white mb-4"></i>
                    <h3 class="text-xl font-semibold text-white mb-2">Conference</h3>
                    <p class="text-orange-100 text-sm">Business & Tech</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose SmartTix?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">We make event discovery and ticket booking simple, secure, and enjoyable</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Easy Discovery</h3>
                <p class="text-gray-600">Find events that match your interests with our powerful search and filtering tools</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Secure Booking</h3>
                <p class="text-gray-600">Your transactions are protected with bank-level security and instant confirmation</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-mobile-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Tickets</h3>
                <p class="text-gray-600">Get your tickets instantly on your phone - no printing required, just show and go</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Discover Your Next Adventure?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Join thousands of event-goers who trust SmartTix for their entertainment needs</p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                <i class="fas fa-calendar-alt mr-2"></i>Explore Events
            </a>
            @guest
            <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                <i class="fas fa-user-plus mr-2"></i>Sign Up Free
            </a>
            @endguest
        </div>
    </div>
</section>
@endsection