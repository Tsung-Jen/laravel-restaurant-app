<?php

namespace Database\Seeders;

use App\Models\User;
use App\Reservations\Models\Reservation;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(OpeningHourSeeder::class);
        $this->call(HolidaySeeder::class);

        if (! User::where('email', 'admin@restaurant.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@restaurant.com',
                'password' => 'password',
            ]);
        }

        if (Reservation::count() === 0) {
            ReservationFactory::new()->count(12)->create();
        }
    }
}
