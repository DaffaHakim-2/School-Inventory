<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanDetail extends Model
{
    protected $fillable = [
        'loan_id',
        'item_id',
        'quantity_requested',
        'quantity_approved',
        'quantity_issued',
    ];

    /**
     * Detail peminjaman dimiliki oleh satu pengajuan.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Detail peminjaman mengacu pada satu barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Satu detail peminjaman dapat memiliki beberapa
     * catatan pengembalian karena pengembalian bertahap.
     */
    public function returnDetails(): HasMany
    {
        return $this->hasMany(ReturnDetail::class);
    }
}