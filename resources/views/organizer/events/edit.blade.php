@extends('layouts.app')

@section('title', 'Edit Event - SmartTix')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
                    <p class="mt-2 text-gray-600">Update your event details</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('organizer.events') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg">
            <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Event Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Title *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="{{ old('title', $event->title) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Enter event title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Description *
                    </label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Describe your event...">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </label>
                        <select id="category_id" name="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Location *
                        </label>
                        <input type="text" id="location" name="location" required
                               value="{{ old('location', $event->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror"
                               placeholder="Event location">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Date *
                        </label>
                        <input type="date" id="date" name="date" required
                               value="{{ old('date', $event->date->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Time *
                        </label>
                        <input type="time" id="time" name="time" required
                               value="{{ old('time', $event->time->format('H:i')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time') border-red-500 @enderror">
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Ticket Price ($) *
                        </label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required
                               value="{{ old('price', $event->price) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">
                            Max Attendees *
                        </label>
                        <input type="number" id="max_attendees" name="max_attendees" min="1" required
                               value="{{ old('max_attendees', $event->max_attendees) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_attendees') border-red-500 @enderror"
                               placeholder="100">
                        @error('max_attendees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="early_bird_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Early Bird Price ($)
                        </label>
                        <input type="number" id="early_bird_price" name="early_bird_price" step="0.01" min="0"
                               value="{{ old('early_bird_price', $event->early_bird_price) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('early_bird_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('early_bird_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Image -->
                @if($event->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <div class="w-32 h-32 rounded-lg overflow-hidden border border-gray-300">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif

                <!-- Event Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $event->image ? 'Update Event Image' : 'Event Image' }}
                    </label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">{{ $event->image ? 'Upload a new image to replace the current one' : 'Upload an image for your event (optional)' }}</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('organizer.events') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
