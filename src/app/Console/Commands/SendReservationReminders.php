<?php

namespace App\Console\Commands;

use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders extends Command
{
    protected $signature = 'reservations:send-reminders';
    protected $description = '予約当日のユーザーへリマインダーメールを送信する';

    public function handle(): int
    {
        $today = Carbon::today();

        $reservations = Reservation::with(['user', 'shop'])
            ->whereDate('date', $today)
            ->whereNull('reminder_sent_at')
            ->get();

        foreach ($reservations as $reservation) {
            if (! $reservation->user?->email) {
                continue;
            }

            Mail::to($reservation->user->email)
                ->send(new ReservationReminderMail($reservation));

            $reservation->reminder_sent_at = now();
            $reservation->save();
        }

        $this->info("Sent reminders for {$reservations->count()} reservations.");

        return Command::SUCCESS;
    }
}
