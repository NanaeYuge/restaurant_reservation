<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'reserved_at',
        'num_of_guests',
        'amount',
        'status',
        'stripe_session_id',
        'visited_at',
    ];

    protected $casts = [
        'user_id'       => 'integer',
        'shop_id'       => 'integer',
        'num_of_guests' => 'integer',
        'amount'        => 'integer',
        'reserved_at'   => 'datetime',
        'visited_at'    => 'datetime',
    ];

    protected $attributes = [
        'status' => 'booked',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeForOwner($q, int $ownerId)
    {
        return $q->whereHas('shop', function ($qq) use ($ownerId) {
            $qq->where('owner_id', $ownerId);
        });
    }
}
