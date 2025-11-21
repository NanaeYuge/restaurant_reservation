<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\QrService;
use Illuminate\View\View;

class MypageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(QrService $qr): View
    {
        $user = auth()->user();

        $reservations = Reservation::with('shop')
            ->where('user_id', $user->id)
            ->orderByDesc('reserved_at')
            ->paginate(10, ['*'], 'resv_page');

        $favorites = $user->favorites()
            ->with(['area', 'genre'])
            ->paginate(6, ['*'], 'fav_page');

        $qrMap = [];
        foreach ($reservations as $r) {
            if (
                (($r->status ?? 'booked') === 'booked') &&
                $r->reserved_at &&
                $r->reserved_at->isFuture()
            ) {
                $qrMap[$r->id] = $qr->forReservation($r->id);
            }
        }

        return view('mypage.index', compact('reservations', 'favorites', 'qrMap'));
    }
}
