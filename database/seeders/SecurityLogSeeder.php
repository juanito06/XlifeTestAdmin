<?php

namespace Database\Seeders;

use App\Models\SecurityLog;
use Illuminate\Database\Seeder;

class SecurityLogSeeder extends Seeder
{
    public function run(): void
    {
        SecurityLog::factory(120)->create();
    }
}