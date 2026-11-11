<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\AdminReturnController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ActivityLogController;

use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserController;

// Halaman utama
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Dashboard pengarah berdasarkan role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route khusus Admin
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        //Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CRUD Kategori
        Route::resource('categories', CategoryController::class);

        // Penyesuaian stok barang
        Route::post(
            'items/{item}/adjust-stock',
            [ItemController::class, 'adjustStock']
        )->name('items.adjust-stock');

        
        // Pengelolaan pengajuan peminjaman
        Route::get('loans', [AdminLoanController::class, 'index'])
            ->name('loans.index');

        Route::get('loans/{loan}', [AdminLoanController::class, 'show'])
            ->name('loans.show');

        Route::post('loans/{loan}/approve', [AdminLoanController::class, 'approve'])
            ->name('loans.approve');

        Route::post('loans/{loan}/reject', [AdminLoanController::class, 'reject'])
            ->name('loans.reject');

        Route::post('loans/{loan}/issue', [AdminLoanController::class, 'issue'])
            ->name('loans.issue');

        // Daftar pengembalian
        Route::get('returns', [AdminReturnController::class, 'index'])
            ->name('returns.index');
            
        // Riwayat aktivitas sistem
        Route::get('activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');
        
        // Manajemen Pengguna
        Route::resource('users', UserController::class);

        // Reset password pengguna
        Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');
            
        // Proses pencatatan pengembalian
        Route::post('loans/{loan}/returns', [AdminReturnController::class, 'store'])
            ->name('loans.returns.store');

        // CRUD Barang
        Route::resource('items', ItemController::class);
    });

// Route khusus User
Route::middleware(['auth', 'verified', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // Pengajuan dan riwayat peminjaman User
        Route::get('/loans', [LoanController::class, 'index'])
            ->name('loans.index');

        Route::get('/loans/create', [LoanController::class, 'create'])
            ->name('loans.create');

        Route::post('/loans', [LoanController::class, 'store'])
            ->name('loans.store');

        Route::get('/loans/{loan}', [LoanController::class, 'show'])
            ->name('loans.show');
    });

// Route Profile
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';