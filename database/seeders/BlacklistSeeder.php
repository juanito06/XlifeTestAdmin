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

        // 15 teléfonos bloqueados — cada uno con su propio número
        for ($i = 0; $i < 15; $i++) {
            Blacklist::create([
                'type'   => 'phone',
                'value'  => fake()->unique()->phoneNumber(),
                'reason' => fake()->randomElement([
                    'Spam', 'Brute force', 'Abusive behavior',
                    'Fraud attempt', 'Bot activity'
                ]),
            ]);
        }
    }
}