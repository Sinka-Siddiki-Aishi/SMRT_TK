<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'review',
        'verified_purchase',
    ];

    protected $casts = [
        'verified_purchase' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Boot method to update event rating when rating is saved
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($rating) {
            $rating->event->updateRating();
        });

        static::deleted(function ($rating) {
            $rating->event->updateRating();
        });
    }
}
