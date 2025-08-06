<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_id',
        'type',
        'discount_percentage',
        'discount_amount',
        'min_quantity',
        'max_quantity',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'active' => 'boolean',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeEarlyBird($query)
    {
        return $query->where('type', 'early_bird');
    }

    // Helper methods
    public function isValid()
    {
        return $this->active &&
               $this->start_date <= now() &&
               $this->end_date >= now() &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount($originalPrice, $quantity = 1)
    {
        if (!$this->isValid() || $quantity < $this->min_quantity) {
            return 0;
        }

        if ($this->max_quantity && $quantity > $this->max_quantity) {
            $quantity = $this->max_quantity;
        }

        if ($this->discount_percentage) {
            return ($originalPrice * $quantity) * ($this->discount_percentage / 100);
        }

        if ($this->discount_amount) {
            return $this->discount_amount * $quantity;
        }

        return 0;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
