<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        // ID user yang sedang login.
        $userId = auth()->id();

        // Total seluruh pengajuan peminjaman.
        $totalLoans = Loan::where('user_id', $userId)->count();

        // Pengajuan yang menunggu persetujuan.
        $pendingLoans = Loan::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Peminjaman yang sedang berlangsung.
        $activeLoans = Loan::where('user_id', $userId)
            ->whereIn('status', [
                'partially_issued',
                'borrowed',
                'partially_returned',
            ])
            ->count();

        // Peminjaman yang sudah selesai dikembalikan.
        $returnedLoans = Loan::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();

        // Lima pengajuan terbaru milik user.
        $latestLoans = Loan::where('user_id', $userId)
            ->withCount('details')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalLoans',
            'pendingLoans',
            'activeLoans',
            'returnedLoans',
            'latestLoans'
        ));
    }
}