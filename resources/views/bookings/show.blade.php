@extends('layouts.app')

@section('title', 'Booking Confirmation - ' . $booking->booking_number)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Success Header -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold mb-4">Booking Confirmed!</h1>
            <p class="text-xl text-green-100">Your tickets have been successfully booked</p>
            <p class="text-green-200 mt-2">Booking #{{ $booking->booking_number }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Information -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Event Details</h2>
                    
                    <div class="flex items-start space-x-6">
                        <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                            <div class="text-center">
                                <div class="text-lg font-bold">{{ $booking->event->date->format('d') }}</div>
                                <div class="text-xs">{{ $booking->event->date->format('M') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $booking->event->title }}</h3>
                            <div class="space-y-2 text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-3 text-blue-500"></i>
                                    {{ $booking->event->date->format('l, F j, Y \a\t g:i A') }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-3 text-blue-500"></i>
                                    {{ $booking->event->venue }}, {{ $booking->event->city }}, {{ $booking->event->state }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-3 text-blue-500"></i>
                                    Organized by {{ $booking->event->organizer->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Booking Summary</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Booking Number:</span>
                            <span class="font-semibold">{{ $booking->booking_number }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Ticket Type:</span>
                            <span class="font-semibold capitalize">{{ $booking->ticket_type }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Quantity:</span>
                            <span class="font-semibold">{{ $booking->quantity }} {{ Str::plural('ticket', $booking->quantity) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Unit Price:</span>
                            <span class="font-semibold">${{ number_format($booking->unit_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">${{ number_format($booking->total_price, 2) }}</span>
                        </div>
                        @if($booking->discount_amount > 0)
                        <div class="flex justify-between py-3 border-b border-gray-200 text-green-600">
                            <span>Discount Applied:</span>
                            <span class="font-semibold">-${{ number_format($booking->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between py-3 text-lg font-bold">
                            <span>Total Paid:</span>
                            <span class="text-green-600">${{ number_format($booking->final_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Your Tickets -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Your Tickets</h2>
                    
                    <div class="space-y-4">
                        @foreach($booking->tickets as $ticket)
                        <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-gray-900">Ticket #{{ $ticket->ticket_number }}</div>
                                <div class="text-sm text-gray-600">{{ ucfirst($ticket->ticket_type) }} Access</div>
                                <div class="text-xs text-gray-500 mt-1">QR Code: {{ $ticket->qr_code }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                                <button onclick="showQRCode('{{ $ticket->qr_code }}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                    <i class="fas fa-qrcode mr-1"></i>QR
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                    
                    <div class="space-y-4">
                        <!-- Download PDF -->
                        <a href="{{ route('bookings.pdf', $booking) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i>
                            Download PDF Tickets
                        </a>

                        <!-- Add to Calendar -->
                        <button onclick="addToCalendar()" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Add to Calendar
                        </button>

                        <!-- Share -->
                        <button onclick="shareBooking()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-share mr-2"></i>
                            Share Event
                        </button>

                        <!-- Cancel Booking -->
                        @if($booking->canBeCancelled())
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancel Booking
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- Important Notes -->
                    <div class="mt-8 p-4 bg-yellow-50 rounded-lg">
                        <h4 class="font-semibold text-yellow-800 mb-2">Important Notes:</h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• Present QR code at venue entrance</li>
                            <li>• Arrive 30 minutes before event</li>
                            <li>• Bring valid ID for verification</li>
                            <li>• No refunds after event starts</li>
                        </ul>
                    </div>

                    <!-- Contact Support -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">Need Help?</h4>
                        <p class="text-sm text-gray-600 mb-3">Contact our support team if you have any questions.</p>
                        <a href="mailto:support@smarttix.com" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-envelope mr-1"></i>support@smarttix.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qr-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 max-w-md mx-4">
        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Ticket QR Code</h3>
            <div id="qr-code-container" class="mb-4">
                <!-- QR code will be generated here -->
            </div>
            <p class="text-sm text-gray-600 mb-4">Show this QR code at the venue entrance</p>
            <button onclick="closeQRModal()" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
function showQRCode(qrCode) {
    const modal = document.getElementById('qr-modal');
    const container = document.getElementById('qr-code-container');
    
    // Clear previous QR code
    container.innerHTML = '';
    
    // Generate QR code
    QRCode.toCanvas(container, qrCode, {
        width: 200,
        height: 200,
        margin: 2
    }, function (error) {
        if (error) console.error(error);
    });
    
    modal.classList.remove('hidden');
}

function closeQRModal() {
    document.getElementById('qr-modal').classList.add('hidden');
}

function addToCalendar() {
    const event = {
        title: "{{ $booking->event->title }}",
        start: "{{ $booking->event->date->format('Y-m-d\TH:i:s') }}",
        location: "{{ $booking->event->venue }}, {{ $booking->event->city }}, {{ $booking->event->state }}",
        description: "{{ $booking->event->description }}"
    };
    
    const startDate = new Date(event.start).toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    const endDate = new Date(new Date(event.start).getTime() + 3 * 60 * 60 * 1000).toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    
    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${startDate}/${endDate}&location=${encodeURIComponent(event.location)}&details=${encodeURIComponent(event.description)}`;
    
    window.open(googleCalendarUrl, '_blank');
}

function shareBooking() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $booking->event->title }}",
            text: "Check out this event I'm attending!",
            url: "{{ route('events.show', $booking->event) }}"
        });
    } else {
        // Fallback to copying URL
        navigator.clipboard.writeText("{{ route('events.show', $booking->event) }}");
        alert('Event URL copied to clipboard!');
    }
}

// Close modal when clicking outside
document.getElementById('qr-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});
</script>
@endpush
