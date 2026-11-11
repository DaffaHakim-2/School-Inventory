<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Menjalankan seeder kategori.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Barang inventaris berupa perangkat elektronik sekolah.',
            ],
            [
                'name' => 'Furnitur',
                'description' => 'Barang inventaris berupa meja, kursi, dan perlengkapan ruangan.',
            ],
            [
                'name' => 'Peralatan Pembelajaran',
                'description' => 'Barang inventaris yang digunakan untuk kegiatan belajar mengajar.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}