<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'approved_by',
        'loan_code',
        'loan_date',
        'planned_return_date',
        'purpose',
        'status',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'planned_return_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Pengajuan peminjaman dibuat oleh satu pengguna.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Peminjaman dapat disetujui oleh satu admin.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Satu peminjaman memiliki banyak detail barang.
     */
    public function details(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    /**
     * Satu peminjaman dapat memiliki beberapa transaksi pengembalian.
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ItemReturn::class);
    }
}