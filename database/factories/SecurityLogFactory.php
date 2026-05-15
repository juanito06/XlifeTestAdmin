<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SecurityLogFactory extends Factory
{
    private array $events = [
        'info'    => ['User login', 'Password changed', 'Profile updated',
                      'Settings modified', 'Session started'],
        'warning' => ['Failed login attempt', 'Unusual location detected',
                      'Multiple sessions open', 'Suspicious activity'],
        'high'    => ['Brute force detected', 'Account compromised',
                      'Unauthorized admin access', 'SQL injection attempt'],
    ];

    public function definition(): array
    {
        $severity = fake()->randomElement(['info','info','warning','high']);

        return [
            'severity'    => $severity,
            'event'       => fake()->randomElement($this->events[$severity]),
            'description' => fake()->sentence(),
            'ip_address'  => fake()->ipv4(),
        ];
    }
}