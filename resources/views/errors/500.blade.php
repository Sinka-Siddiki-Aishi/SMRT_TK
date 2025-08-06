@extends('layouts.app')

@section('title', 'Server Error - SmartTix')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                <i class="fas fa-server text-red-600 text-3xl"></i>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Server Error</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Something went wrong on our end. We're working to fix this issue. Please try again later.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Go Home
                </a>
                
                <button onclick="window.location.reload()" 
                        class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i>
                    Try Again
                </button>
            </div>
            
            <!-- Contact Support -->
            <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                <p class="text-blue-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    If this problem persists, please contact our support team.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
