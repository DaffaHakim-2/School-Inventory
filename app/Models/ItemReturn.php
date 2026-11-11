<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemReturn extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'received_by',
        'return_code',
        'return_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
        ];
    }

    /**
     * Transaksi pengembalian berasal dari satu peminjaman.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Pengembalian diterima oleh satu admin.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Satu transaksi pengembalian memiliki banyak detail.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }
}