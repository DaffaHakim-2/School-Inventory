<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman milik User.
     */
    public function index(): View
    {
        $loans = Loan::withCount('details')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.loans.index', compact('loans'));
    }

    /**
     * Menampilkan form pengajuan peminjaman.
     */
    public function create(): View
    {
        $items = Item::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('user.loans.create', compact('items'));
    }

    /**
     * Menyimpan pengajuan beserta detail barang.
     */
    public function store(
        StoreLoanRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $loan = DB::transaction(function () use ($data) {

            // Membuat kode peminjaman unik.
            do {
                $loanCode = 'PNM-' . now()->format('Ymd')
                    . '-' . strtoupper(Str::random(6));
            } while (Loan::where('loan_code', $loanCode)->exists());

            // Menyimpan data utama peminjaman.
            $loan = Loan::create([
                'user_id' => auth()->id(),
                'loan_code' => $loanCode,
                'loan_date' => $data['loan_date'],
                'planned_return_date' => $data['planned_return_date'],
                'purpose' => $data['purpose'] ?? null,
                'status' => 'pending',
            ]);

            // Menyimpan setiap barang yang diajukan.
            foreach ($data['items'] as $index => $detail) {

                // Mengunci data barang selama transaksi berlangsung.
                $item = Item::whereKey($detail['item_id'])
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->first();

                if (!$item) {
                    throw ValidationException::withMessages([
                        "items.{$index}.item_id" =>
                            'Barang tidak ditemukan atau sudah dihapus.',
                    ]);
                }

                // Memastikan jumlah permintaan tidak melebihi stok.
                if ($detail['quantity_requested'] > $item->stock) {
                    throw ValidationException::withMessages([
                        "items.{$index}.quantity_requested" =>
                            "Stok {$item->name} hanya tersedia {$item->stock} unit.",
                    ]);
                }

                $loan->details()->create([
                    'item_id' => $item->id,
                    'quantity_requested' => $detail['quantity_requested'],
                    'quantity_approved' => 0,
                    'quantity_issued' => 0,
                ]);
            }

            // Mengembalikan data pengajuan setelah transaksi selesai.
            return $loan;
        });

        // Mengarahkan User ke halaman detail pengajuan.
        return redirect()
            ->route('user.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }
    public function show(Loan $loan): View
    {
        // User hanya boleh melihat pengajuannya sendiri.
        abort_unless(
            $loan->user_id === auth()->id(),
            403,
            'Anda tidak memiliki akses ke pengajuan ini.'
        );

        $loan->load([
            'details.item.category',
            'approver',
        ]);

        return view('user.loans.show', compact('loan'));
    }
}