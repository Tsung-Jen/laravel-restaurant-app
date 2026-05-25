<?php

namespace Database\Seeders;

use App\OpeningHours\Models\OpeningHour;
use Illuminate\Database\Seeder;

class OpeningHourSeeder extends Seeder
{
    public function run(): void
    {
        $hours = [
            ['day_of_week' => 0, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            ['day_of_week' => 1, 'lunch_start' => null,    'lunch_end' => null,    'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            ['day_of_week' => 2, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            ['day_of_week' => 3, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            ['day_of_week' => 4, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => true,  'closed_except_week' => null],
            ['day_of_week' => 5, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
            ['day_of_week' => 6, 'lunch_start' => '11:30', 'lunch_end' => '14:30', 'evening_start' => '17:30', 'evening_end' => '23:00', 'is_closed' => false, 'closed_except_week' => null],
        ];

        foreach ($hours as $hour) {
            OpeningHour::updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                $hour
            );
        }
    }
}
