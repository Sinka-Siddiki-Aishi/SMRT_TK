<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $booking->event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .logo::before {
            content: '🎫';
            margin-right: 10px;
        }
        
        .ticket-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        
        .booking-number {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .ticket-body {
            padding: 30px;
        }
        
        .event-info {
            margin-bottom: 30px;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .event-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: table-row;
        }
        
        .detail-label {
            display: table-cell;
            font-weight: bold;
            color: #666;
            padding: 8px 20px 8px 0;
            width: 150px;
        }
        
        .detail-value {
            display: table-cell;
            color: #333;
            padding: 8px 0;
        }
        
        .tickets-section {
            border-top: 2px dashed #ddd;
            padding-top: 30px;
            margin-top: 30px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .ticket-item {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .ticket-item:last-child {
            margin-bottom: 0;
        }
        
        .ticket-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .ticket-details {
            flex: 1;
        }
        
        .ticket-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .ticket-type {
            color: #666;
            margin-bottom: 10px;
        }
        
        .qr-code-section {
            text-align: center;
            min-width: 120px;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            border: 2px solid #ddd;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-size: 12px;
            color: #666;
            border-radius: 8px;
        }
        
        .qr-text {
            font-size: 10px;
            color: #666;
            word-break: break-all;
            line-height: 1.2;
        }
        
        .booking-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-row:last-child {
            margin-bottom: 0;
            font-weight: bold;
            font-size: 18px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .important-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .important-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
        
        .important-list {
            color: #856404;
            margin: 0;
            padding-left: 20px;
        }
        
        .important-list li {
            margin-bottom: 5px;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        
        @media print {
            body {
                background-color: white;
            }
            
            .ticket-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="logo">SmartTix</div>
            <div class="ticket-title">Event Ticket</div>
            <div class="booking-number">Booking #{{ $booking->booking_number }}</div>
        </div>
        
        <!-- Body -->
        <div class="ticket-body">
            <!-- Event Information -->
            <div class="event-info">
                <h1 class="event-title">{{ $booking->event->title }}</h1>
                
                <div class="event-details">
                    <div class="detail-row">
                        <div class="detail-label">Date & Time:</div>
                        <div class="detail-value">{{ $booking->event->date->format('l, F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Venue:</div>
                        <div class="detail-value">{{ $booking->event->venue }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Address:</div>
                        <div class="detail-value">{{ $booking->event->address }}, {{ $booking->event->city }}, {{ $booking->event->state }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Organizer:</div>
                        <div class="detail-value">{{ $booking->event->organizer->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Category:</div>
                        <div class="detail-value">{{ $booking->event->category->name }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="event-details">
                <div class="detail-row">
                    <div class="detail-label">Customer:</div>
                    <div class="detail-value">{{ $booking->user->name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $booking->user->email }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Booking Date:</div>
                    <div class="detail-value">{{ $booking->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>
            
            <!-- Tickets Section -->
            <div class="tickets-section">
                <h2 class="section-title">Your Tickets ({{ $booking->quantity }})</h2>
                
                @foreach($booking->tickets as $ticket)
                <div class="ticket-item">
                    <div class="ticket-info">
                        <div class="ticket-details">
                            <div class="ticket-number">Ticket #{{ $ticket->ticket_number }}</div>
                            <div class="ticket-type">{{ ucfirst($ticket->ticket_type) }} Access</div>
                            <div style="color: #666; font-size: 14px;">
                                Status: <strong style="color: #28a745;">{{ ucfirst($ticket->status) }}</strong>
                            </div>
                        </div>
                        <div class="qr-code-section">
                            <div class="qr-code">
                                <img src="{{ $ticket->generateQRCode(150) }}" alt="QR Code" style="width: 100px; height: 100px; border-radius: 4px;">
                            </div>
                            <div class="qr-text">{{ $ticket->qr_code }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Booking Summary -->
            <div class="booking-summary">
                <h3 style="margin-top: 0; margin-bottom: 15px;">Booking Summary</h3>
                
                <div class="summary-row">
                    <span>Ticket Type:</span>
                    <span>{{ ucfirst($booking->ticket_type) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Quantity:</span>
                    <span>{{ $booking->quantity }} {{ Str::plural('ticket', $booking->quantity) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Unit Price:</span>
                    <span>৳{{ number_format($booking->unit_price, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>৳{{ number_format($booking->total_price, 2) }}</span>
                </div>
                
                @if($booking->discount_amount > 0)
                <div class="summary-row" style="color: #28a745;">
                    <span>Discount:</span>
                    <span>-৳{{ number_format($booking->discount_amount, 2) }}</span>
                </div>
                @endif
                
                <div class="summary-row">
                    <span>Total Paid:</span>
                    <span>${{ number_format($booking->final_price, 2) }}</span>
                </div>
            </div>
            
            <!-- Important Information -->
            <div class="important-info">
                <div class="important-title">Important Information:</div>
                <ul class="important-list">
                    <li>Present this ticket (QR code) at the venue entrance</li>
                    <li>Arrive at least 30 minutes before the event starts</li>
                    <li>Bring a valid photo ID for verification</li>
                    <li>This ticket is non-transferable and non-refundable after the event starts</li>
                    <li>Keep this ticket safe - lost tickets cannot be replaced</li>
                    <li>For support, contact us at support@smarttix.com</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }} | SmartTix - Smart Ticketing & Event Management</p>
            <p>For questions or support, visit our website or email support@smarttix.com</p>
        </div>
    </div>
</body>
</html>