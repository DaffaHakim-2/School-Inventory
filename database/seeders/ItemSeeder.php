<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Menjalankan seeder barang inventaris.
     */
    public function run(): void
    {
        $items = [
            // Kategori Elektronik
            [
                'category' => 'Elektronik',
                'code' => 'ELK-001',
                'name' => 'Laptop Lenovo',
                'description' => 'Laptop untuk kegiatan administrasi dan pembelajaran.',
                'stock' => 10,
            ],
            [
                'category' => 'Elektronik',
                'code' => 'ELK-002',
                'name' => 'Proyektor Epson',
                'description' => 'Proyektor untuk presentasi dan kegiatan belajar.',
                'stock' => 8,
            ],
            [
                'category' => 'Elektronik',
                'code' => 'ELK-003',
                'name' => 'Printer Canon',
                'description' => 'Printer untuk kebutuhan pencetakan dokumen.',
                'stock' => 5,
            ],
            [
                'category' => 'Elektronik',
                'code' => 'ELK-004',
                'name' => 'Speaker Aktif',
                'description' => 'Speaker untuk kegiatan pembelajaran dan acara sekolah.',
                'stock' => 6,
            ],

            // Kategori Furnitur
            [
                'category' => 'Furnitur',
                'code' => 'FUR-001',
                'name' => 'Meja Siswa',
                'description' => 'Meja untuk kegiatan belajar siswa.',
                'stock' => 30,
            ],
            [
                'category' => 'Furnitur',
                'code' => 'FUR-002',
                'name' => 'Kursi Siswa',
                'description' => 'Kursi untuk kegiatan belajar siswa.',
                'stock' => 40,
            ],
            [
                'category' => 'Furnitur',
                'code' => 'FUR-003',
                'name' => 'Lemari Arsip',
                'description' => 'Lemari untuk menyimpan dokumen sekolah.',
                'stock' => 5,
            ],

            // Kategori Peralatan Pembelajaran
            [
                'category' => 'Peralatan Pembelajaran',
                'code' => 'PEL-001',
                'name' => 'Globe',
                'description' => 'Alat peraga untuk pembelajaran geografi.',
                'stock' => 4,
            ],
            [
                'category' => 'Peralatan Pembelajaran',
                'code' => 'PEL-002',
                'name' => 'Papan Tulis',
                'description' => 'Papan tulis untuk kegiatan pembelajaran.',
                'stock' => 10,
            ],
            [
                'category' => 'Peralatan Pembelajaran',
                'code' => 'PEL-003',
                'name' => 'Mikroskop',
                'description' => 'Alat praktikum untuk pembelajaran IPA.',
                'stock' => 6,
            ],
        ];

        foreach ($items as $itemData) {
            $category = Category::where(
                'name',
                $itemData['category']
            )->firstOrFail();

            Item::updateOrCreate(
                ['code' => $itemData['code']],
                [
                    'category_id' => $category->id,
                    'name' => $itemData['name'],
                    'description' => $itemData['description'],
                    'stock' => $itemData['stock'],
                ]
            );
        }
    }
}