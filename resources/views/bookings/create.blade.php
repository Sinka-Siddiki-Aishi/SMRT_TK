@extends('layouts.app')

@section('title', 'Book Tickets - ' . $event->title)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Event Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-blue-200 hover:text-white mr-4">
                    <i class="fas fa-arrow-left"></i> Back to Event
                </a>
            </div>
            <h1 class="text-3xl font-bold mb-2">Book Tickets</h1>
            <h2 class="text-xl text-blue-100">{{ $event->title }}</h2>
            <div class="flex items-center mt-2 text-blue-100">
                <i class="fas fa-calendar mr-2"></i>
                {{ $event->date->format('l, F j, Y \a\t g:i A') }}
                <span class="mx-4">•</span>
                <i class="fas fa-map-marker-alt mr-2"></i>
                {{ $event->venue }}, {{ $event->city }}
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Select Your Tickets</h3>
                    
                    <form action="{{ route('bookings.store', $event) }}" method="POST" id="booking-form">
                        @csrf
                        
                        <!-- Ticket Type Selection -->
                        <div class="mb-8">
                            <label class="block text-lg font-semibold text-gray-900 mb-4">Ticket Type</label>
                            <div class="space-y-4">
                                <!-- General Admission -->
                                <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer ticket-option" data-type="general" data-price="{{ $pricing['general'] }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="radio" name="ticket_type" value="general" id="general" class="mr-3" required>
                                            <div>
                                                <label for="general" class="text-lg font-medium text-gray-900 cursor-pointer">General Admission</label>
                                                <p class="text-gray-600">Standard access to the event</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600">${{ $pricing['general'] }}</div>
                                            @if($event->price != $pricing['general'])
                                                <div class="text-sm text-gray-500 line-through">${{ $event->price }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- VIP -->
                                @if($event->vip_price)
                                <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer ticket-option" data-type="vip" data-price="{{ $pricing['vip'] }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="radio" name="ticket_type" value="vip" id="vip" class="mr-3">
                                            <div>
                                                <label for="vip" class="text-lg font-medium text-gray-900 cursor-pointer">VIP Access</label>
                                                <p class="text-gray-600">Premium seating and exclusive perks</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-purple-600">${{ $pricing['vip'] }}</div>
                                            @if($event->vip_price != $pricing['vip'])
                                                <div class="text-sm text-gray-500 line-through">${{ $event->vip_price }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Premium -->
                                @if($event->premium_price)
                                <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer ticket-option" data-type="premium" data-price="{{ $pricing['premium'] }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="radio" name="ticket_type" value="premium" id="premium" class="mr-3">
                                            <div>
                                                <label for="premium" class="text-lg font-medium text-gray-900 cursor-pointer">Premium Experience</label>
                                                <p class="text-gray-600">Best seats and VIP treatment</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-yellow-600">${{ $pricing['premium'] }}</div>
                                            @if($event->premium_price != $pricing['premium'])
                                                <div class="text-sm text-gray-500 line-through">${{ $event->premium_price }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <div class="mb-8">
                            <label for="quantity" class="block text-lg font-semibold text-gray-900 mb-4">Number of Tickets</label>
                            <select name="quantity" id="quantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                @for($i = 1; $i <= min(10, $event->available_tickets); $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ Str::plural('ticket', $i) }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Active Deals -->
                        @if($activeDeals->count() > 0)
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">🎉 Available Deals</h4>
                            <div class="space-y-3">
                                @foreach($activeDeals as $deal)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="font-semibold text-green-800">{{ $deal->title }}</h5>
                                            <p class="text-green-600 text-sm">{{ $deal->description }}</p>
                                        </div>
                                        <div class="text-green-800 font-bold">
                                            @if($deal->discount_percentage)
                                                {{ $deal->discount_percentage }}% OFF
                                            @else
                                                ${{ $deal->discount_amount }} OFF
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-semibold text-lg transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>
                    
                    <!-- Event Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div><i class="fas fa-calendar mr-2"></i>{{ $event->date->format('M d, Y') }}</div>
                            <div><i class="fas fa-clock mr-2"></i>{{ $event->date->format('g:i A') }}</div>
                            <div><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->venue }}</div>
                        </div>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div id="pricing-summary" class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ticket Type:</span>
                            <span id="selected-type" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quantity:</span>
                            <span id="selected-quantity" class="font-medium">1</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Unit Price:</span>
                            <span id="unit-price" class="font-medium">$0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal" class="font-medium">$0</span>
                        </div>
                        <div id="discount-row" class="flex justify-between text-green-600 hidden">
                            <span>Discount:</span>
                            <span id="discount-amount">-$0</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span id="total-price" class="text-blue-600">$0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                            Secure booking with instant confirmation
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketOptions = document.querySelectorAll('.ticket-option');
    const quantitySelect = document.getElementById('quantity');
    const selectedType = document.getElementById('selected-type');
    const selectedQuantity = document.getElementById('selected-quantity');
    const unitPrice = document.getElementById('unit-price');
    const subtotal = document.getElementById('subtotal');
    const totalPrice = document.getElementById('total-price');

    function updatePricing() {
        const selectedTicket = document.querySelector('input[name="ticket_type"]:checked');
        const quantity = parseInt(quantitySelect.value);

        if (selectedTicket) {
            const type = selectedTicket.value;
            const price = parseFloat(selectedTicket.closest('.ticket-option').dataset.price);
            
            selectedType.textContent = type.charAt(0).toUpperCase() + type.slice(1);
            selectedQuantity.textContent = quantity;
            unitPrice.textContent = '$' + price.toFixed(2);
            
            const subtotalAmount = price * quantity;
            subtotal.textContent = '$' + subtotalAmount.toFixed(2);
            totalPrice.textContent = '$' + subtotalAmount.toFixed(2);
        }
    }

    // Add click handlers for ticket options
    ticketOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update visual selection
            ticketOptions.forEach(opt => opt.classList.remove('border-blue-500', 'bg-blue-50'));
            this.classList.add('border-blue-500', 'bg-blue-50');
            
            updatePricing();
        });
    });

    quantitySelect.addEventListener('change', updatePricing);

    // Initialize with first option selected
    if (ticketOptions.length > 0) {
        ticketOptions[0].click();
    }
});
</script>
@endpush
