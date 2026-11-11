<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi pengembalian
            $table->foreignId('return_id')
                ->constrained('returns')
                ->restrictOnDelete();

            // Relasi ke detail barang yang dipinjam
            $table->foreignId('loan_detail_id')
                ->constrained('loan_details')
                ->restrictOnDelete();

            // Jumlah barang yang dikembalikan
            $table->unsignedInteger('quantity_returned');

            // Kondisi barang saat dikembalikan
            $table->enum('condition', [
                'good',
                'damaged',
                'lost',
            ]);

            // Catatan kondisi barang boleh kosong
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};