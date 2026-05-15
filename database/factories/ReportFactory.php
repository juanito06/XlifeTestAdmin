<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        $reportable = fake()->randomElement([
            Post::all()->random(),
            Comment::all()->random(),
        ]);

        return [
            'user_id'         => User::all()->random()->id,
            'reportable_id'   => $reportable->id,
            'reportable_type' => get_class($reportable),
            'type'            => fake()->randomElement([
                                    'spam','harassment',
                                    'misinformation','nudity','other'
                                 ]),
            'status'          => fake()->randomElement([
                                    'pending','pending','resolved','dismissed'
                                 ]),
            'reason'          => fake()->sentence(),
        ];
    }
}