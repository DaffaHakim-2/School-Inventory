<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        // Menghitung jumlah kategori aktif
        $totalCategories = Category::count();

        // Menghitung jumlah jenis barang aktif
        $totalItems = Item::count();

        // Menghitung total stok barang yang tersedia
        $totalStock = Item::sum('stock');

        // Menghitung pengajuan yang menunggu persetujuan
        $pendingLoans = Loan::where('status', 'pending')->count();

        // Mengambil lima peminjaman terbaru
        $latestLoans = Loan::with('user')
            ->withCount('details')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalItems',
            'totalStock',
            'pendingLoans',
            'latestLoans'
        ));
    }
}