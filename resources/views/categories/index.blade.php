@extends('layouts.app')

@section('title', 'Event Categories - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Event Categories</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Discover events that match your interests. From music to sports, we have something for everyone.
                </p>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->id) }}" class="group">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Category Header -->
                            <div class="relative h-32 bg-gradient-to-br 
                                @switch($category->name)
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
                                <div class="relative h-full flex items-center justify-center">
                                    <i class="
                                        @switch($category->name)
                                            @case('Music')
                                                fas fa-music
                                                @break
                                            @case('Sports')
                                                fas fa-futbol
                                                @break
                                            @case('Theater')
                                                fas fa-theater-masks
                                                @break
                                            @case('Conference')
                                                fas fa-users
                                                @break
                                            @default
                                                fas fa-calendar
                                        @endswitch
                                        text-4xl text-white
                                    "></i>
                                </div>
                                
                                <!-- Event Count Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white bg-opacity-90 text-gray-900 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $category->events_count }} events
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Category Info -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                    {{ $category->name }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-4">
                                    @switch($category->name)
                                        @case('Music')
                                            Concerts, festivals, and live performances
                                            @break
                                        @case('Sports')
                                            Games, tournaments, and sporting events
                                            @break
                                        @case('Theater')
                                            Plays, musicals, and theatrical performances
                                            @break
                                        @case('Conference')
                                            Business meetings, tech talks, and seminars
                                            @break
                                        @default
                                            Various events and activities
                                    @endswitch
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        {{ $category->events_count }} {{ Str::plural('event', $category->events_count) }}
                                    </span>
                                    
                                    <div class="flex items-center text-blue-600 text-sm font-medium group-hover:text-blue-700">
                                        Explore
                                        <i class="fas fa-arrow-right ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tags text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No categories available</h3>
                <p class="text-gray-600 mb-6">Categories will appear here once they are created</p>
            </div>
        @endif
    </div>

    <!-- Popular Categories Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Categories?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Browse events by category to quickly find exactly what you're looking for
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-filter text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy Filtering</h3>
                    <p class="text-gray-600">Quickly narrow down events to match your specific interests and preferences</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Personalized Experience</h3>
                    <p class="text-gray-600">Discover events tailored to your tastes and discover new interests</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Save Time</h3>
                    <p class="text-gray-600">Find what you're looking for faster with organized event categories</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Can't Find What You're Looking For?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Browse all events or use our search feature to find exactly what you need
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}" 
                   class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Browse All Events
                </a>
                
                <a href="{{ route('events.index') }}?search=" 
                   class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>Search Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
