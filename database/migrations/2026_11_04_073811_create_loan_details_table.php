<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke pengajuan peminjaman
            $table->foreignId('loan_id')
                ->constrained('loans')
                ->restrictOnDelete();

            // Relasi ke barang yang dipinjam
            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            // Jumlah barang yang diminta oleh User
            $table->unsignedInteger('quantity_requested');

            // Jumlah barang yang disetujui Admin
            $table->unsignedInteger('quantity_approved')->default(0);

            // Jumlah barang yang benar-benar diserahkan
            $table->unsignedInteger('quantity_issued')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_details');
    }
};