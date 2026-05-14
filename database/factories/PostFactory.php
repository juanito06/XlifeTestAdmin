<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'title'   => fake()->sentence(6),
            'content' => fake()->paragraphs(3, true),
            'status'  => fake()->randomElement([
                            'published','published','published',
                            'pending','removed'
                         ]),
            'views'   => fake()->numberBetween(0, 50000),
            'likes'   => fake()->numberBetween(0, 5000),
            'shares'  => fake()->numberBetween(0, 1000),
        ];
    }
}