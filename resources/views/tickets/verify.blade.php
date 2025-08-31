@extends('layouts.app')

@section('title', 'Verify Ticket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md">
        @if(isset($status) && $status === 'valid')
            <div class="bg-green-500 text-white p-8 rounded-t-lg text-center">
                <h1 class="text-3xl font-bold">Ticket Valid</h1>
                <p class="mt-2">{{ $message }}</p>
            </div>
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $ticket->event->title }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div><strong>Ticket Holder:</strong> {{ $ticket->user->name }}</div>
                    <div><strong>Ticket Type:</strong> {{ ucfirst($ticket->ticket_type) }}</div>
                    <div><strong>Event Date:</strong> {{ $ticket->event->date->format('l, F j, Y') }}</div>
                    <div><strong>Venue:</strong> {{ $ticket->event->venue }}</div>
                    <div><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</div>
                    <div><strong>Status:</strong> <span class="font-semibold text-green-600">{{ ucfirst($ticket->status) }}</span></div>
                </div>
            </div>
        @elseif(isset($status) && $status === 'used')
            <div class="bg-yellow-500 text-white p-8 rounded-t-lg text-center">
                <h1 class="text-3xl font-bold">Ticket Already Used</h1>
                <p class="mt-2">{{ $message }}</p>
            </div>
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $ticket->event->title }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div><strong>Ticket Holder:</strong> {{ $ticket->user->name }}</div>
                    <div><strong>Ticket Type:</strong> {{ ucfirst($ticket->ticket_type) }}</div>
                    <div><strong>Used At:</strong> {{ $ticket->used_at->format('l, F j, Y H:i A') }}</div>
                    <div><strong>Status:</strong> <span class="font-semibold text-yellow-600">{{ ucfirst($ticket->status) }}</span></div>
                </div>
            </div>
        @else
            <div class="bg-red-500 text-white p-8 rounded-lg text-center">
                <h1 class="text-3xl font-bold">Ticket Invalid</h1>
                <p class="mt-2">{{ $message ?? 'An unknown error occurred.' }}</p>
            </div>
        @endif
    </div>
</div>
@endsection