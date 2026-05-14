<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SecuritySettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'two_factor_required'     => false,
            'ip_blocking_enabled'     => true,
            'max_login_attempts'      => 5,
            'session_timeout_minutes' => 60,
        ];
    }
}