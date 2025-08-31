<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
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

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function deposit(float $amount, string $description)
    {
        $balance_before = $this->wallet->balance;
        $this->wallet->increment('balance', $amount);
        return $this->wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'deposit',
            'description' => $description,
            'balance_before' => $balance_before,
            'balance_after' => $this->wallet->balance,
        ]);
    }

    public function withdraw(float $amount, string $description)
    {
        if ($this->wallet->balance < $amount) {
            return false;
        }

        $balance_before = $this->wallet->balance;
        $this->wallet->decrement('balance', $amount);
        return $this->wallet->transactions()->create([
            'amount' => $amount,
            'type' => 'withdraw',
            'description' => $description,
            'balance_before' => $balance_before,
            'balance_after' => $this->wallet->balance,
        ]);
    }

    // Helper methods
    public function isOrganizer()
    {
        return $this->role === 'organizer';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}