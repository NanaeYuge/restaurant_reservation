<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $base = Reservation::with(['shop:id,name,owner_id', 'user:id,name,email'])->latest('reserved_at');

        if (method_exists($user, 'roles') && $user->roles()->where('name','admin')->exists()) {
            $reservations = $base->paginate(20)->withQueryString();
        } else {
            $reservations = $base->whereHas('shop', fn($q) => $q->where('owner_id', $user->id))
                                 ->paginate(20)->withQueryString();
        }

        return view('owner.reservations.index', compact('reservations'));
    }

    public function show(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (!(method_exists($user, 'roles') && $user->roles()->where('name','admin')->exists())) {
            if (!$reservation->shop || $reservation->shop->owner_id !== $user->id) {
                abort(403);
            }
        }

        $reservation->load(['shop','user']);
        return view('owner.reservations.show', compact('reservation'));
    }

    public function confirm(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (!(method_exists($user, 'roles') && $user->roles()->where('name','admin')->exists())) {
            if (!$reservation->shop || $reservation->shop->owner_id !== $user->id) {
                abort(403);
            }
        }

        $reservation->status = 'confirmed';
        $reservation->save();

        return back()->with('success', '予約を確定しました。');
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        $user = $request->user();

        if (!(method_exists($user, 'roles') && $user->roles()->where('name','admin')->exists())) {
            if (!$reservation->shop || $reservation->shop->owner_id !== $user->id) {
                abort(403);
            }
        }

        $reservation->status = 'canceled';
        $reservation->save();

        return back()->with('success', '予約をキャンセルしました。');
    }
}
