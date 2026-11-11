<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // User yang mengajukan peminjaman
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Admin yang menyetujui pengajuan
            // Nullable karena pengajuan belum tentu disetujui
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            // Kode peminjaman harus unik
            $table->string('loan_code')->unique();

            // Tanggal peminjaman dan rencana pengembalian
            $table->date('loan_date');
            $table->date('planned_return_date');

            // Tujuan peminjaman boleh kosong
            $table->text('purpose')->nullable();

            // Status pengajuan peminjaman
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'partially_issued',
                'borrowed',
                'partially_returned',
                'returned',
                'cancelled',
            ])->default('pending');

            // Waktu persetujuan oleh Admin
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};