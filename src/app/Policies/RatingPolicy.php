<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Rating;

class RatingPolicy
{

    public function create(User $user, Shop $shop): bool
    {
        $hasEligibleReservation = Reservation::query()
            ->where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->where(function ($q) {
                $now = now();

                $q->when(schema_has_column('reservations', 'visited_at'), function ($q) use ($now) {
                    $q->orWhere('visited_at', '<=', $now);
                });

                $q->when(schema_has_column('reservations', 'reserved_at'), function ($q) use ($now) {
                    $q->orWhere('reserved_at', '<=', $now);
                });

                $q->where(function ($qq) use ($now) {
                    $hasDate = schema_has_column('reservations', 'reservation_date');
                    $hasTime = schema_has_column('reservations', 'reservation_time');
                    if ($hasDate && $hasTime) {
                        $qq->orWhere(function ($qqq) use ($now) {
                            $qqq->where('reservation_date', '<=', $now->toDateString())
                                ->where('reservation_time', '<=', $now->format('H:i:s'));
                        });
                    } elseif ($hasDate) {
                        $qq->orWhere('reservation_date', '<=', $now->toDateString());
                    }
                });

                $q->orWhere('payment_status', 'paid');
            })
            ->exists();

        if (! $hasEligibleReservation) {
            return false;
        }

        $alreadyRated = Rating::query()
            ->where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->exists();

        return ! $alreadyRated;
    }


    public function update(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }

    public function delete(User $user, Rating $rating): bool
    {
        return $rating->user_id === $user->id;
    }
}

if (! function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
