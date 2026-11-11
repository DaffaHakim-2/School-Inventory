<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnDetail extends Model
{
    protected $fillable = [
        'return_id',
        'loan_detail_id',
        'quantity_returned',
        'condition',
        'notes',
    ];

    /**
     * Detail pengembalian dimiliki oleh satu transaksi pengembalian.
     */
    public function itemReturn(): BelongsTo
    {
        return $this->belongsTo(ItemReturn::class, 'return_id');
    }

    /**
     * Detail pengembalian mengacu pada detail peminjaman tertentu.
     */
    public function loanDetail(): BelongsTo
    {
        return $this->belongsTo(LoanDetail::class);
    }
}