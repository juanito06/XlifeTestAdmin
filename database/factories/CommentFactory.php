<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => \App\Models\Post::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'body'    => fake()->paragraph(),
            'status'  => fake()->randomElement([
                            'visible','visible','visible','hidden','removed'
                         ]),
        ];
    }
}