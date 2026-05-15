<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlacklistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'   => 'ip',
            'value'  => fake()->ipv4(),
            'reason' => fake()->randomElement([
                            'Spam', 'Brute force', 'Abusive behavior',
                            'Fraud attempt', 'Bot activity'
                        ]),
        ];
    }
}