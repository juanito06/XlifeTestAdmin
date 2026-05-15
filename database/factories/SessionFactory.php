<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    public function definition(): array
    {
        $start    = fake()->dateTimeBetween('-4 weeks', 'now');
        $duration = fake()->numberBetween(1, 180);
        $end      = (clone $start)->modify("+{$duration} minutes");

        return [
            'user_id'          => User::all()->random()->id,
            'started_at'       => $start,
            'ended_at'         => $end,
            'duration_minutes' => $duration,
        ];
    }
}