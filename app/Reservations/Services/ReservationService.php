<?php

namespace App\Reservations\Services;

use App\Reservations\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    const SESSIONS = [
        'lunch'  => ['start' => '11:30', 'end' => '14:30'],
        'dinner' => ['start' => '17:30', 'end' => '23:00'],
    ];

    const MAX_CAPACITY = 55;

    public function getSession(string $time): ?string
    {
        [$h, $m] = explode(':', $time);
        $minutes = (int) $h * 60 + (int) $m;

        foreach (self::SESSIONS as $name => $slot) {
            $startMinutes = $this->timeToMinutes($slot['start']);
            $endMinutes = $this->timeToMinutes($slot['end']);
            if ($minutes >= $startMinutes && $minutes < $endMinutes) {
                return $name;
            }
        }

        return null;
    }

    public function getBookedCount(string $date, string $session): int
    {
        return Reservation::whereDate('date', $date)
            ->where('status', '!=', 'cancelled')
            ->whereRaw($this->sessionSql(), [$session])
            ->sum('guests');
    }

    public function isSessionFull(string $date, string $session): bool
    {
        return $this->getBookedCount($date, $session) >= self::MAX_CAPACITY;
    }

    public function getRemainingCapacity(string $date, string $session): int
    {
        return max(0, self::MAX_CAPACITY - $this->getBookedCount($date, $session));
    }

    public function sessionSql(): string
    {
        return "CASE
            WHEN time >= '11:30' AND time < '14:30' THEN 'lunch'
            WHEN time >= '17:30' AND time < '23:00' THEN 'dinner'
        END = ?";
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $m] = explode(':', $time);
        return (int) $h * 60 + (int) $m;
    }
}
