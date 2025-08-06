<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'end_date',
        'location',
        'venue',
        'address',
        'city',
        'state',
        'country',
        'category_id',
        'organizer_id',
        'price',
        'vip_price',
        'premium_price',
        'capacity',
        'available_tickets',
        'image',
        'performers',
        'status',
        'featured',
        'rating',
        'rating_count',
        'booking_count',
    ];

    protected $casts = [
        'date' => 'datetime',
        'end_date' => 'datetime',
        'performers' => 'array',
        'featured' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>', now());
    }

    public function scopeTopRated($query)
    {
        return $query->where('rating', '>=', 4.0)->orderBy('rating', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('booking_count', 'desc');
    }

    // Helper methods
    public function getAvailableTicketsAttribute()
    {
        return $this->capacity - $this->bookings()->where('status', 'confirmed')->sum('quantity');
    }

    public function hasAvailableTickets($quantity = 1)
    {
        return $this->available_tickets >= $quantity;
    }

    public function updateRating()
    {
        $ratings = $this->ratings();
        $this->rating = $ratings->avg('rating') ?? 0;
        $this->rating_count = $ratings->count();
        $this->save();
    }

    public function getSmartPricing($ticketType = 'general')
    {
        $basePrice = $this->price;

        if ($ticketType === 'vip') {
            $basePrice = $this->vip_price ?? ($this->price * 1.5);
        } elseif ($ticketType === 'premium') {
            $basePrice = $this->premium_price ?? ($this->price * 2);
        }

        // Apply demand-based pricing
        $demandMultiplier = 1;
        $availabilityRatio = $this->available_tickets / $this->capacity;

        if ($availabilityRatio < 0.1) {
            $demandMultiplier = 1.3; // High demand
        } elseif ($availabilityRatio < 0.3) {
            $demandMultiplier = 1.15; // Medium demand
        }

        return round($basePrice * $demandMultiplier, 2);
    }
}