<?php

namespace App\OpeningHours\Services;

use App\OpeningHours\Models\Holiday;
use App\OpeningHours\Models\OpeningHour;
use App\OpeningHours\Models\Vacation;
use Carbon\Carbon;

class OpeningHoursService
{
    public function getAll(): array
    {
        $rows = OpeningHour::orderBy('day_of_week')->get()->keyBy('day_of_week');
        $days = [];
        foreach (range(0, 6) as $day) {
            $days[$day] = $rows->get($day)?->toArray() ?? $this->defaults($day);
        }
        return $days;
    }

    public function getHolidays(): array
    {
        return Holiday::whereYear('date', Carbon::now()->year)->orderBy('date')->get()->toArray();
    }

    public function isHoliday(string $date): bool
    {
        return Holiday::whereDate('date', $date)->exists();
    }

    public function isOnVacation(string $date): bool
    {
        return Vacation::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }

    public function getActiveVacations(): array
    {
        $today = Carbon::now()->format('Y-m-d');
        return Vacation::where('end_date', '>=', $today)
            ->orderBy('start_date')
            ->get()
            ->toArray();
    }

    public function isOpen(string $date, string $time): bool
    {
        // Vacation always closes the restaurant
        if ($this->isOnVacation($date)) {
            return false;
        }

        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $timeMinutes = $this->timeToMinutes($time);

        $row = OpeningHour::where('day_of_week', $dayOfWeek)->first();

        if (! $row) {
            $defaults = $this->defaults($dayOfWeek);
            return $this->checkSlot($defaults, $timeMinutes, $date);
        }

        return $this->checkSlot($row->toArray(), $timeMinutes, $date);
    }

    private function checkSlot(array $row, int $timeMinutes, string $date): bool
    {
        $isClosed = $row['is_closed'] ?? false;
        $exceptWeek = $row['closed_except_week'] ?? null;
        $currentWeek = Carbon::parse($date)->format('o-\WW');

        if ($isClosed) {
            // Check if this date is a public holiday (any day of week)
            $isHoliday = $this->isHoliday($date);

            // Check if current week matches closed_except_week
            $matchesWeek = $exceptWeek && $currentWeek === $exceptWeek;

            if ($isHoliday || $matchesWeek) {
                return $this->checkTimeRange($timeMinutes, $row['lunch_start'], $row['lunch_end'])
                    || $this->checkTimeRange($timeMinutes, $row['evening_start'], $row['evening_end']);
            }

            return false;
        }

        if ($row['lunch_start'] && $row['lunch_end']) {
            if ($this->checkTimeRange($timeMinutes, $row['lunch_start'], $row['lunch_end'])) {
                return true;
            }
        }

        if ($row['evening_start'] && $row['evening_end']) {
            if ($this->checkTimeRange($timeMinutes, $row['evening_start'], $row['evening_end'])) {
                return true;
            }
        }

        return false;
    }

    private function checkTimeRange(int $timeMinutes, ?string $start, ?string $end): bool
    {
        if (! $start || ! $end) {
            return false;
        }
        return $timeMinutes >= $this->timeToMinutes($start)
            && $timeMinutes < $this->timeToMinutes($end);
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $m] = explode(':', $time);
        return (int) $h * 60 + (int) $m;
    }

    private function defaults(int $dayOfWeek): array
    {
        return match ($dayOfWeek) {
            0 => ['day_of_week' => 0, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            1 => ['day_of_week' => 1, 'lunch_start' => null, 'lunch_end' => null, 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            2 => ['day_of_week' => 2, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            3 => ['day_of_week' => 3, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            4 => ['day_of_week' => 4, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => true, 'closed_except_week' => null],
            5 => ['day_of_week' => 5, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            6 => ['day_of_week' => 6, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
        };
    }
}
