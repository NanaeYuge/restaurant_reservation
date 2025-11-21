<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrService
{
    public function forReservation(int $reservationId): string
    {
        $url = URL::signedRoute('reservations.checkin', ['reservation' => $reservationId]);

        $svg = QrCode::format('svg')
            ->size(240)
            ->margin(0)
            ->errorCorrection('M')
            ->generate($url);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
