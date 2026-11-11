<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnRequest;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\ItemReturn;
use App\Models\Loan;
use App\Models\ReturnDetail;
use App\Http\Requests\IndexReturnRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminReturnController extends Controller
{

    /**
     * Menampilkan daftar pengembalian barang.
     */
    
/**
 * Menampilkan daftar peminjaman yang masih memiliki
 * barang yang belum dikembalikan.
 */
public function index(IndexReturnRequest $request)
{
    $search = $request->validated('search');

$loans = Loan::with([
    'user',
    'details.item',
    'details.returnDetails',
])
        ->whereIn('status', [
            'borrowed',
            'partially_returned',
        ])
        ->when($search, function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('loan_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.returns.index', compact(
        'loans',
        'search'
    ));
}

    /**
     * Mencatat pengembalian barang dari peminjaman.
     */
    public function store(
        StoreReturnRequest $request,
        Loan $loan
    ): RedirectResponse {

        DB::transaction(function () use ($request, $loan) {

            // Mengunci data peminjaman selama proses pengembalian.
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pengembalian hanya dapat dilakukan untuk peminjaman
            // yang seluruh barang yang disetujui sudah diserahkan.
            if (!in_array($loan->status, [
                'borrowed',
                'partially_returned',
            ])) {
                throw ValidationException::withMessages([
                    'loan' => 'Peminjaman ini belum dapat menerima pengembalian.',
                ]);
            }

            $data = $request->validated();

            $submittedItems = collect($data['items']);

            // Mengunci seluruh detail barang milik peminjaman.
            $details = $loan->details()
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Memastikan semua detail yang dikirim milik peminjaman ini.
            foreach ($submittedItems as $submittedItem) {
                if (!$details->has($submittedItem['loan_detail_id'])) {
                    throw ValidationException::withMessages([
                        'items' => 'Detail barang tidak sesuai dengan peminjaman ini.',
                    ]);
                }
            }

            // Membuat kode pengembalian unik.
            do {
                $returnCode = 'PNG-' . now()->format('Ymd')
                    . '-' . strtoupper(Str::random(6));
            } while (ItemReturn::where('return_code', $returnCode)->exists());

            // Membuat transaksi utama pengembalian.
            $itemReturn = ItemReturn::create([
                'loan_id' => $loan->id,
                'received_by' => auth()->id(),
                'return_code' => $returnCode,
                'return_date' => $data['return_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $totalReturnedNow = 0;

            foreach ($submittedItems as $submittedItem) {

                $detail = $details->get(
                    $submittedItem['loan_detail_id']
                );

                $quantityReturned = (int) $submittedItem['quantity_returned'];

                // Menghitung seluruh jumlah yang pernah dikembalikan
                // untuk detail peminjaman ini.
                $previouslyReturned = ReturnDetail::where(
                    'loan_detail_id',
                    $detail->id
                )->sum('quantity_returned');

                // Menghitung sisa barang yang belum dikembalikan.
                $remainingQuantity = $detail->quantity_issued
                    - $previouslyReturned;

                if ($quantityReturned > $remainingQuantity) {
                    throw ValidationException::withMessages([
                        'items' =>
                            "Jumlah pengembalian melebihi sisa barang yang belum dikembalikan.",
                    ]);
                }

                // Menyimpan rincian pengembalian.
                $itemReturn->details()->create([
                    'loan_detail_id' => $detail->id,
                    'quantity_returned' => $quantityReturned,
                    'condition' => $submittedItem['condition'],
                    'notes' => $submittedItem['notes'] ?? null,
                ]);

                // Hanya barang berkondisi baik yang menambah stok.
                if ($submittedItem['condition'] === 'good') {

                    // Mengunci stok barang.
                    $item = Item::withTrashed()
                        ->whereKey($detail->item_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$item) {
                        throw ValidationException::withMessages([
                            'items' => 'Data barang tidak ditemukan.',
                        ]);
                    }

                    $stockBefore = $item->stock;
                    $stockAfter = $stockBefore + $quantityReturned;

                    // Menambahkan stok barang.
                    $item->update([
                        'stock' => $stockAfter,
                    ]);

                    // Mencatat pergerakan stok.
                    ItemMovement::create([
                        'item_id' => $item->id,
                        'user_id' => auth()->id(),
                        'loan_id' => $loan->id,
                        'return_id' => $itemReturn->id,
                        'movement_type' => 'return_in',
                        'quantity' => $quantityReturned,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'description' =>
                            "Pengembalian barang berkondisi baik untuk peminjaman {$loan->loan_code}.",
                        'created_at' => now(),
                    ]);
                }

                $totalReturnedNow += $quantityReturned;
            }

            // Menghitung total barang yang sudah diserahkan.
            $totalIssued = $details->sum('quantity_issued');

            // Menghitung seluruh barang yang sudah dikembalikan,
            // termasuk pengembalian pada transaksi sebelumnya.
            $totalReturned = ReturnDetail::whereIn(
                'loan_detail_id',
                $details->keys()
            )->sum('quantity_returned');

            // Memperbarui status peminjaman.
            $loan->update([
                'status' => $totalReturned >= $totalIssued
                    ? 'returned'
                    : 'partially_returned',
            ]);

            // Mencatat aktivitas Admin.
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'loan_returned',
                'description' =>
                    "Admin mencatat pengembalian {$totalReturnedNow} barang "
                    . "untuk peminjaman {$loan->loan_code}. "
                    . "Total dikembalikan: {$totalReturned} dari {$totalIssued} barang.",
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengembalian barang berhasil dicatat.');
    }
}