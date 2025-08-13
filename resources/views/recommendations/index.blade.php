@extends('layouts.app')

@section('title', 'Recommended Events')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Recommended for You</h1>
        <p class="text-gray-600 mt-2">Events we think you'll love based on your interests</p>
    </div>

    @if(isset($recommendations) && $recommendations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $event)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white text-xl font-bold">{{ substr($event->title, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($event->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $event->date->format('M d, Y') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $event->location }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-green-600">
                                From ${{ number_format($event->price, 2) }}
                            </span>
                            <a href="{{ route('events.show', $event) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                View Event
                            </a>
                        </div>
                        
                        @if($event->rating)
                            <div class="mt-3 flex items-center">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $event->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">({{ $event->rating }}/5)</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(isset($popularEvents) && $popularEvents->count() > 0)
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Popular Events</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($popularEvents as $event)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                            <span class="text-white text-xl font-bold">{{ substr($event->title, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($event->description, 100) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $event->date->format('M d, Y') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $event->location }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-green-600">
                                From ${{ number_format($event->price, 2) }}
                            </span>
                            <a href="{{ route('events.show', $event) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                View Event
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Recommendations Available</h3>
            <p class="text-gray-500">Start booking events to get personalized recommendations!</p>
            <a href="{{ route('events.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                Browse All Events
            </a>
        </div>
    @endif
</div>
@endsection