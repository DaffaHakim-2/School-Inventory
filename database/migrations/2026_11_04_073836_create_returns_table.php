<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();

            // Relasi ke transaksi peminjaman
            $table->foreignId('loan_id')
                ->constrained('loans')
                ->restrictOnDelete();

            // Admin yang menerima pengembalian barang
            $table->foreignId('received_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Kode pengembalian harus unik
            $table->string('return_code')->unique();

            // Tanggal pengembalian
            $table->date('return_date');

            // Catatan pengembalian boleh kosong
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};