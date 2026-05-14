<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // El orden importa — primero usuarios, luego todo lo demás
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            SessionSeeder::class,
            ReportSeeder::class,
            SecurityLogSeeder::class,
            BlacklistSeeder::class,
            SecuritySettingSeeder::class,
        ]);
    }
}