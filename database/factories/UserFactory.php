<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $countries = [
            'IT','IT','IT','ES','ES','MX','MX','US','US',
            'BR','AR','FR','DE','PT','CO','CL','PE','VE'
        ];

        return [
            'name'               => fake()->name(),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => Hash::make('password'),
            'remember_token'     => \Illuminate\Support\Str::random(10),
            'status'             => fake()->randomElement([
                                    'active','active','active','suspended','banned'
                                   ]),
            'country'            => fake()->randomElement($countries),
            'last_login_at'      => fake()->dateTimeBetween('-4 weeks', 'now'),
        ];
    }
}