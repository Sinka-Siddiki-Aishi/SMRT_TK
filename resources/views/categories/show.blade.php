@extends('layouts.app')

@section('title', $category->name . ' Events - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-gradient-to-br 
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
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-white text-opacity-80">
                    <li><a href="{{ url('/') }}" class="hover:text-white">Home</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-white">Categories</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-white">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-6">
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
                        text-3xl text-white
                    "></i>
                </div>
                
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">{{ $category->name }} Events</h1>
                    <p class="text-xl text-white text-opacity-90">
                        {{ $category->events->count() }} {{ Str::plural('event', $category->events->count()) }} available
                    </p>
                </div>
            </div>
            
            <p class="text-xl text-white text-opacity-90 max-w-2xl">
                @switch($category->name)
                    @case('Music')
                        Discover amazing concerts, festivals, and live music performances that will make your heart sing.
                        @break
                    @case('Sports')
                        Get your adrenaline pumping with exciting games, tournaments, and sporting events.
                        @break
                    @case('Theater')
                        Experience the magic of live theater with captivating plays, musicals, and performances.
                        @break
                    @case('Conference')
                        Expand your knowledge and network at professional conferences, seminars, and business events.
                        @break
                    @default
                        Explore a variety of exciting events in this category.
                @endswitch
            </p>
        </div>
    </div>

    <!-- Events Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($category->events->count() > 0)
            <!-- Filter Bar -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $category->events->count() }} {{ Str::plural('Event', $category->events->count()) }} Found
                        </h2>
                        <p class="text-gray-600">Showing all {{ strtolower($category->name) }} events</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Sort by Date</option>
                            <option>Sort by Price</option>
                            <option>Sort by Popularity</option>
                        </select>
                        
                        <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>
            
            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($category->events as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                        <!-- Event Image/Header -->
                        <div class="relative h-48 bg-gradient-to-r 
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
                            
                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 bg-white rounded-lg p-3 text-center shadow-md">
                                <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                <div class="text-xs text-gray-600 uppercase">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                            </div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-semibold">
                                    Featured
                                </span>
                            </div>
                        </div>
                        
                        <!-- Event Details -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($event->date)->format('M d, Y \a\t g:i A') }}
                                </div>
                                
                                <div class="flex items-center text-gray-500 text-sm">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $event->location }}
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center justify-between">
                                <div class="text-blue-600 font-semibold">
                                    From ৳{{ rand(25, 150) }}
                                </div>
                                
                                <a href="{{ route('events.show', $event->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No events in this category yet</h3>
                <p class="text-gray-600 mb-6">Check back soon for new {{ strtolower($category->name) }} events</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('categories.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Browse Other Categories
                    </a>
                    <a href="{{ route('events.index') }}" 
                       class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        View All Events
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Related Categories -->
    @if($category->events->count() > 0)
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Explore Other Categories</h2>
                <p class="text-xl text-gray-600">Discover more amazing events across different categories</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $otherCategories = [
                        ['name' => 'Music', 'icon' => 'fas fa-music', 'color' => 'from-purple-500 to-pink-500'],
                        ['name' => 'Sports', 'icon' => 'fas fa-futbol', 'color' => 'from-blue-500 to-cyan-500'],
                        ['name' => 'Theater', 'icon' => 'fas fa-theater-masks', 'color' => 'from-green-500 to-emerald-500'],
                        ['name' => 'Conference', 'icon' => 'fas fa-users', 'color' => 'from-orange-500 to-red-500']
                    ];
                @endphp
                
                @foreach($otherCategories as $cat)
                    @if($cat['name'] !== $category->name)
                        <a href="{{ route('categories.show', 1) }}" class="group">
                            <div class="bg-gradient-to-br {{ $cat['color'] }} rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                <i class="{{ $cat['icon'] }} text-3xl text-white mb-3"></i>
                                <h3 class="text-lg font-semibold text-white">{{ $cat['name'] }}</h3>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection