<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel categories
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Kode barang harus unik
            $table->string('code')->unique();

            // Informasi barang
            $table->string('name');
            $table->text('description')->nullable();

            // Jumlah stok awal adalah 0
            $table->unsignedInteger('stock')->default(0);

            // created_at, updated_at, deleted_at
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};