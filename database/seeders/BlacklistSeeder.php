<?php

namespace Database\Seeders;

use App\Models\Blacklist;
use Illuminate\Database\Seeder;

class BlacklistSeeder extends Seeder
{
    public function run(): void
    {
        // 20 IPs bloqueadas
        Blacklist::factory(20)->create([
            'type' => 'ip',
        ]);

        // 15 teléfonos bloqueados
        Blacklist::factory(15)->create([
            'type'  => 'phone',
            'value' => fake()->phoneNumber(),
        ]);
    }
}