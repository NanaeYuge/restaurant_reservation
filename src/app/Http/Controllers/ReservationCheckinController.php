<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class ReservationCheckinController extends Controller
{
    public function show(Reservation $reservation)
    {
        return view('reservations.checkin', compact('reservation'));
    }
}
