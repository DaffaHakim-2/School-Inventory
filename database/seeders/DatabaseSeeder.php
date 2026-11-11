<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seluruh seeder aplikasi.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            LoanSeeder::class,
            ReturnSeeder::class,
            ItemMovementSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}