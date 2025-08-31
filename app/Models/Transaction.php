<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'description',
        'balance_before',
        'balance_after',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}