<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\LoanDetail;
use App\Models\ReturnDetail;
use App\Models\ItemMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemMovementSeeder extends Seeder
{
    /**
     * Menjalankan seeder pergerakan stok.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // Mengambil seluruh barang.
            $items = Item::all();

            foreach ($items as $item) {

                // Stok awal berasal dari ItemSeeder.
                $currentStock = $item->stock;

                // Mencatat stok awal.
                ItemMovement::updateOrCreate(
                    [
                        'item_id' => $item->id,
                        'movement_type' => 'initial_stock',
                        'loan_id' => null,
                        'return_id' => null,
                    ],
                    [
                        'user_id' => null,
                        'quantity' => $currentStock,
                        'stock_before' => 0,
                        'stock_after' => $currentStock,
                        'description' => 'Pencatatan stok awal inventaris.',
                        'created_at' => '2026-10-01 08:00:00',
                    ]
                );

                // Mengumpulkan seluruh pergerakan barang.
                $movements = [];

                // Mengambil detail peminjaman barang ini.
                $loanDetails = LoanDetail::with('loan')
                    ->where('item_id', $item->id)
                    ->where('quantity_issued', '>', 0)
                    ->get();

                foreach ($loanDetails as $detail) {
                    $movements[] = [
                        'type' => 'loan_out',
                        'date' => $detail->loan->loan_date,
                        'quantity' => $detail->quantity_issued,
                        'loan_id' => $detail->loan_id,
                        'return_id' => null,
                        'description' => 'Barang keluar karena peminjaman '
                            . $detail->loan->loan_code,
                    ];
                }

                // Mengambil detail pengembalian barang ini.
                $returnDetails = ReturnDetail::with('itemReturn')
                    ->whereHas('loanDetail', function ($query) use ($item) {
                        $query->where('item_id', $item->id);
                    })
                    ->get();

                foreach ($returnDetails as $detail) {

                    // Barang kondisi baik dikembalikan ke stok tersedia.
                    if ($detail->condition === 'good') {
                        $movements[] = [
                            'type' => 'return_in',
                            'date' => $detail->itemReturn->return_date,
                            'quantity' => $detail->quantity_returned,
                            'loan_id' => $detail->itemReturn->loan_id,
                            'return_id' => $detail->return_id,
                            'description' => 'Barang kembali dari pengembalian '
                                . $detail->itemReturn->return_code,
                        ];
                    }
                }

                // Mengurutkan pergerakan berdasarkan tanggal.
                usort($movements, function ($a, $b) {
                    return $a['date'] <=> $b['date'];
                });

                // Memproses setiap pergerakan stok.
                foreach ($movements as $movement) {

                    $stockBefore = $currentStock;

                    // Peminjaman mengurangi stok.
                    // Pengembalian menambah stok.
                    $quantity = $movement['type'] === 'loan_out'
                        ? -$movement['quantity']
                        : $movement['quantity'];

                    $currentStock += $quantity;

                    // Pengaman agar stok tidak negatif.
                    if ($currentStock < 0) {
                        throw new \RuntimeException(
                            "Stok barang {$item->name} tidak boleh negatif."
                        );
                    }

                    ItemMovement::updateOrCreate(
                        [
                            'item_id' => $item->id,
                            'movement_type' => $movement['type'],
                            'loan_id' => $movement['loan_id'],
                            'return_id' => $movement['return_id'],
                        ],
                        [
                            'user_id' => null,
                            'quantity' => $quantity,
                            'stock_before' => $stockBefore,
                            'stock_after' => $currentStock,
                            'description' => $movement['description'],
                            'created_at' => $movement['date'],
                        ]
                    );
                }

                // Menyimpan stok akhir ke tabel items.
                $item->update([
                    'stock' => $currentStock,
                ]);
            }
        });
    }
}