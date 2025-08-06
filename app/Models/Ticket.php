<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'booking_id',
        'user_id',
        'event_id',
        'ticket_type',
        'qr_code',
        'status',
        'used_at',
        'seat_number',
        'section',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Helper methods
    public function markAsUsed()
    {
        $this->update([
            'status' => 'used',
            'used_at' => now(),
        ]);
    }

    public function isValid()
    {
        return $this->status === 'active' &&
               $this->event->date > now();
    }

    public function getQRCodeUrl()
    {
        return route('tickets.verify', $this->qr_code);
    }
}
