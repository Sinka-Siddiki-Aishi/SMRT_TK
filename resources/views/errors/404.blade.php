@extends('layouts.app')

@section('title', 'Page Not Found - SmartTix')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Sorry, we couldn't find the page you're looking for. The page might have been moved, deleted, or you entered the wrong URL.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Go Home
                </a>
                
                <a href="{{ route('events.index') }}" 
                   class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Browse Events
                </a>
            </div>
            
            <!-- Search -->
            <div class="mt-8">
                <p class="text-gray-600 mb-4">Or search for what you're looking for:</p>
                <form action="{{ route('events.index') }}" method="GET" class="max-w-md mx-auto">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Search events..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-r-lg transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
