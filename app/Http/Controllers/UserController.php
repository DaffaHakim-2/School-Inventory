<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetUserPasswordRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index(IndexUserRequest $request)
    {
        $validated = $request->validated();

        $users = User::query()
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($validated['role'] ?? null, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan formulir tambah pengguna.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'user_created',
                'description' => 'Menambahkan pengguna baru: '
                    . $user->name . ' (' . $user->email . ').',
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna.
     */
    public function show(User $user)
    {
        $user->loadCount([
            'loans',
            'approvedLoans',
            'receivedReturns',
            'itemMovements',
            'activityLogs',
        ]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan formulir edit pengguna.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Admin tidak boleh mengubah role akunnya sendiri.
        if (
            $user->id === auth()->id()
            && $validated['role'] !== $user->role
        ) {
            throw ValidationException::withMessages([
                'role' => 'Kamu tidak dapat mengubah role akun sendiri.',
            ]);
        }

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'user_updated',
                'description' => 'Memperbarui data pengguna: '
                    . $user->name . ' (' . $user->email . ').',
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Mengatur ulang password pengguna.
     */
    public function resetPassword(
        ResetUserPasswordRequest $request,
        User $user
    ) {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'user_password_reset',
                'description' => 'Mengatur ulang password pengguna: '
                    . $user->name . ' (' . $user->email . ').',
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Password pengguna berhasil diatur ulang.');
    }

    /**
     * Menghapus pengguna jika tidak memiliki riwayat.
     */
    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akunnya sendiri.
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
                'Kamu tidak dapat menghapus akun sendiri.'
            );
        }

        DB::transaction(function () use ($user) {
            // Kunci baris pengguna selama proses penghapusan.
            $targetUser = User::whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Periksa seluruh relasi yang memiliki riwayat.
            $hasHistory =
                $targetUser->loans()->exists()
                || $targetUser->approvedLoans()->exists()
                || $targetUser->receivedReturns()->exists()
                || $targetUser->itemMovements()->exists()
                || $targetUser->activityLogs()->exists();

            if ($hasHistory) {
                throw ValidationException::withMessages([
                    'user' => 'Pengguna tidak dapat dihapus karena memiliki '
                        . 'riwayat transaksi atau aktivitas.',
                ]);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'user_deleted',
                'description' => 'Menghapus pengguna: '
                    . $targetUser->name . ' (' . $targetUser->email . ').',
                'created_at' => now(),
            ]);

            $targetUser->delete();
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}