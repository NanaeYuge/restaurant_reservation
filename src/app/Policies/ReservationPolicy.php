<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReservationPolicy
{
    public function update(User $user, Reservation $reservation): bool
    {
        // 本人の予約のみ
        if ($reservation->user_id !== $user->id) return false;

        // 変更締切: 来店日前日23:59まで（＝当日は不可）
        $visit = $reservation->reserved_at; // datetime カラム想定（後述の置換メモ参照）
        $deadline = Carbon::parse($visit)->startOfDay()->subDay()->endOfDay(); // = visit日の前日 23:59:59
        return now()->lte($deadline);
    }
}
