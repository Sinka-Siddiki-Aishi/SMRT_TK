<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'event_id',
        'quantity',
        'ticket_type',
        'unit_price',
        'total_price',
        'discount_amount',
        'final_price',
        'status',
        'payment_method',
        'payment_id',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_number = 'BK' . strtoupper(Str::random(8));
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Helper methods
    public function generateTickets()
    {
        for ($i = 0; $i < $this->quantity; $i++) {
            Ticket::create([
                'ticket_number' => 'TK' . strtoupper(Str::random(10)),
                'booking_id' => $this->id,
                'user_id' => $this->user_id,
                'event_id' => $this->event_id,
                'ticket_type' => $this->ticket_type,
                'qr_code' => $this->generateQRCode(),
            ]);
        }
    }

    private function generateQRCode()
    {
        return 'QR' . strtoupper(Str::random(16));
    }

    public function canBeCancelled()
    {
        return $this->status === 'confirmed' &&
               $this->event->date > now()->addHours(24);
    }
}
