<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'user_id',
        'loan_id',
        'return_id',
        'movement_type',
        'quantity',
        'stock_before',
        'stock_after',
        'description',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Pergerakan stok mengacu pada satu barang.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Pengguna yang mencatat pergerakan stok.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pergerakan stok yang berkaitan dengan peminjaman.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Pergerakan stok yang berkaitan dengan pengembalian.
     */
    public function itemReturn(): BelongsTo
    {
        return $this->belongsTo(ItemReturn::class, 'return_id');
    }
}