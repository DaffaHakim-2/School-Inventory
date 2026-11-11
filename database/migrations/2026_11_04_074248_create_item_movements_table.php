<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_movements', function (Blueprint $table) {
            $table->id();

            // Barang yang mengalami perubahan stok
            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            // User atau Admin yang mencatat perubahan stok
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // Relasi transaksi peminjaman (opsional)
            $table->foreignId('loan_id')
                ->nullable()
                ->constrained('loans')
                ->restrictOnDelete();

            // Relasi transaksi pengembalian (opsional)
            $table->foreignId('return_id')
                ->nullable()
                ->constrained('returns')
                ->restrictOnDelete();

            // Jenis perubahan stok
            $table->enum('movement_type', [
                'initial_stock',
                'stock_in',
                'stock_adjustment',
                'loan_out',
                'return_in',
            ]);

            // Jumlah perubahan stok
            $table->integer('quantity');

            // Kondisi stok sebelum dan sesudah perubahan
            $table->integer('stock_before');
            $table->integer('stock_after');

            // Keterangan perubahan stok
            $table->text('description')->nullable();

            // Tabel ini hanya membutuhkan created_at
            $table->timestamp('created_at')->useCurrent();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_movements');
    }
};