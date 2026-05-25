<?php

namespace Database\Factories;

use App\Reservations\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'guests' => fake()->numberBetween(1, 8),
            'date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'time' => fake()->randomElement(['17:00', '18:00', '19:00', '20:00', '21:00']),
            'notes' => fake()->optional(0.4)->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
