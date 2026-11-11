<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Pengajuan peminjaman yang dibuat oleh pengguna.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Peminjaman yang disetujui oleh admin.
     */
    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    /**
     * Pengembalian yang diterima oleh pengguna/admin.
     */
    public function receivedReturns(): HasMany
    {
        return $this->hasMany(ItemReturn::class, 'received_by');
    }

    /**
     * Riwayat pergerakan stok yang dicatat pengguna.
     */
    public function itemMovements(): HasMany
    {
        return $this->hasMany(ItemMovement::class);
    }

    /**
     * Riwayat aktivitas pengguna.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}