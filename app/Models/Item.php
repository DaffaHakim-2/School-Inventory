<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'stock',
    ];

    /**
     * Barang dimiliki oleh satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Barang dapat muncul dalam banyak detail peminjaman.
     */
    public function loanDetails(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    /**
     * Barang memiliki banyak catatan pergerakan stok.
     */
    public function itemMovements(): HasMany
    {
        return $this->hasMany(ItemMovement::class);
    }
}