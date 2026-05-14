<?php

namespace Database\Seeders;

use App\Models\SecuritySetting;
use Illuminate\Database\Seeder;

class SecuritySettingSeeder extends Seeder
{
    public function run(): void
    {
        // Solo una fila de configuración global
        SecuritySetting::create([
            'two_factor_required'     => false,
            'ip_blocking_enabled'     => true,
            'max_login_attempts'      => 5,
            'session_timeout_minutes' => 60,
        ]);
    }
}