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

    public function canBeCancelled()
    {
        return $this->event->date->isFuture();
    }
}