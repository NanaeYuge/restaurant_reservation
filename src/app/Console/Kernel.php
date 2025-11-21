<?php

namespace App\Console;

use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            Reservation::with(['user', 'shop'])
                ->whereDate('reserved_at', today())
                ->get()
                ->each(function ($r) {
                    if ($r->user) {
                        $r->user->notify(new ReservationReminder($r));
                    }
                });
        })->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
