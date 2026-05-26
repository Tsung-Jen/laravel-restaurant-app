<?php

namespace App\Reservations\Console;

use App\Reservations\Models\Reservation;
use Illuminate\Console\Command;

class CleanupExpiredReservations extends Command
{
    protected $signature = 'reservations:cleanup';

    protected $description = 'Delete expired reservation records from the database';

    public function handle(): void
    {
        $deleted = Reservation::whereDate('date', '<', today())->delete();

        $this->info("Deleted {$deleted} expired reservation(s).");
    }
}
