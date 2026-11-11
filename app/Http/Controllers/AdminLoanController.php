<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveLoanRequest;
use App\Http\Requests\RejectLoanRequest;
use App\Http\Requests\IssueLoanRequest;

use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\ItemMovement;
use App\Models\Loan;
use App\Models\ItemReturn;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoanController extends Controller
{
    /**
     * Menampilkan seluruh pengajuan peminjaman.
     */
    public function index(): View
    {
        $loans = Loan::with('user')
            ->withCount('details')
            ->latest()
            ->paginate(10);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Menampilkan detail pengajuan peminjaman.
     */

/**
 * Menampilkan detail pengajuan peminjaman beserta riwayat pengembalian.
 */
public function show(Loan $loan): View
{
    // Mengambil informasi peminjaman dan rincian barang.
    $loan->load([
        'user',
        'approver',
        'details.item.category',
        'details.returnDetails',
    ]);

    // Mengambil seluruh transaksi pengembalian untuk peminjaman ini.
    $returns = ItemReturn::with([
        'receiver',
        'details.loanDetail.item',
    ])
        ->where('loan_id', $loan->id)
        ->orderByDesc('return_date')
        ->orderByDesc('id')
        ->get();

    return view('admin.loans.show', compact('loan', 'returns'));
}


    /**
     * Menyetujui pengajuan peminjaman.
     */
    public function approve(ApproveLoanRequest $request, Loan $loan)
    {
        DB::transaction(function () use ($request, $loan) {

            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'pending') {
                throw ValidationException::withMessages([
                    'loan' => 'Pengajuan ini sudah diproses sebelumnya.',
                ]);
            }

            $submittedItems = collect(
                $request->validated()['items']
            );

            $details = $loan->details()
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($submittedItems->count() !== $details->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Semua rincian barang harus disertakan.',
                ]);
            }

            foreach ($submittedItems as $submittedItem) {
                $detail = $details->get(
                    $submittedItem['loan_detail_id']
                );

                if (!$detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Rincian barang tidak sesuai dengan pengajuan ini.',
                    ]);
                }

                $quantityApproved = (int) $submittedItem['quantity_approved'];

                if ($quantityApproved > $detail->quantity_requested) {
                    throw ValidationException::withMessages([
                        'items' => 'Jumlah yang disetujui tidak boleh melebihi jumlah yang diajukan.',
                    ]);
                }

                $detail->update([
                    'quantity_approved' => $quantityApproved,
                ]);
            }

            $totalApproved = $details->sum('quantity_approved');

            $loan->update([
                'status' => $totalApproved > 0 ? 'approved' : 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => $totalApproved > 0
                    ? 'loan_approved'
                    : 'loan_rejected',
                'description' => $totalApproved > 0
                    ? "Pengajuan {$loan->loan_code} disetujui dengan total {$totalApproved} barang."
                    : "Pengajuan {$loan->loan_code} otomatis ditolak karena seluruh jumlah barang disetujui adalah 0.",
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil diproses.');
    }
    /**
     * Menolak pengajuan peminjaman.
     */
    public function reject(RejectLoanRequest $request, Loan $loan)
    {
        DB::transaction(function () use ($request, $loan) {

            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($loan->status !== 'pending') {
                throw ValidationException::withMessages([
                    'loan' => 'Pengajuan ini sudah diproses sebelumnya.',
                ]);
            }

            $loan->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'loan_rejected',
                'description' => "Pengajuan {$loan->loan_code} ditolak. Alasan: "
                    . $request->validated()['rejection_reason'],
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }
    /**
     * Mencatat penyerahan barang kepada peminjam.
     */
    public function issue(IssueLoanRequest $request, Loan $loan)
    {
        DB::transaction(function () use ($request, $loan) {

            // Kunci data pengajuan agar tidak diproses bersamaan.
            $loan = Loan::whereKey($loan->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Penyerahan hanya untuk pengajuan yang sudah disetujui.
            if (!in_array($loan->status, ['approved', 'partially_issued'])) {
                throw ValidationException::withMessages([
                    'loan' => 'Pengajuan ini belum dapat menerima penyerahan barang.',
                ]);
            }

            $submittedItems = collect(
                $request->validated()['items']
            );

            // Kunci seluruh rincian pengajuan.
            $details = $loan->details()
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($submittedItems->count() !== $details->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Semua rincian barang harus disertakan.',
                ]);
            }

            $totalIssuedNow = 0;

            foreach ($submittedItems as $submittedItem) {

                $detail = $details->get(
                    $submittedItem['loan_detail_id']
                );

                if (!$detail) {
                    throw ValidationException::withMessages([
                        'items' => 'Rincian barang tidak sesuai dengan pengajuan ini.',
                    ]);
                }

                $quantityIssuedNow = (int) $submittedItem['quantity_issued'];

                // Jumlah yang diserahkan tidak boleh melebihi sisa persetujuan.
                $remainingQuantity = $detail->quantity_approved
                    - $detail->quantity_issued;

                if ($quantityIssuedNow > $remainingQuantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Jumlah penyerahan melebihi sisa barang yang disetujui.',
                    ]);
                }

                // Tidak perlu memperbarui stok jika jumlahnya 0.
                if ($quantityIssuedNow === 0) {
                    continue;
                }

                // Kunci stok barang agar aman dari perubahan bersamaan.
                $item = Item::whereKey($detail->item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$item) {
                    throw ValidationException::withMessages([
                        'items' => 'Barang tidak ditemukan atau sudah tidak tersedia.',
                    ]);
                }

                if ($item->stock < $quantityIssuedNow) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$item->name} tidak mencukupi.",
                    ]);
                }

                $stockBefore = $item->stock;
                $stockAfter = $stockBefore - $quantityIssuedNow;

                // Kurangi stok barang.
                $item->update([
                    'stock' => $stockAfter,
                ]);

                // Tambahkan jumlah kumulatif barang yang telah diserahkan.
                $detail->update([
                    'quantity_issued' => $detail->quantity_issued
                        + $quantityIssuedNow,
                ]);

                // Catat pergerakan stok.
                ItemMovement::create([
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'loan_id' => $loan->id,
                    'return_id' => null,
                    'movement_type' => 'loan_out',
                    'quantity' => -$quantityIssuedNow,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'description' => "Penyerahan barang untuk peminjaman {$loan->loan_code}.",
                    'created_at' => now(),
                ]);

                $totalIssuedNow += $quantityIssuedNow;
            }

            // Minimal harus ada satu barang yang diserahkan.
            if ($totalIssuedNow === 0) {
                throw ValidationException::withMessages([
                    'items' => 'Masukkan minimal satu barang untuk diserahkan.',
                ]);
            }

            // Hitung total barang yang disetujui dan sudah diserahkan.
            $totalApproved = $details->sum('quantity_approved');
            $totalIssued = $details->sum('quantity_issued');

            // Perbarui status pengajuan.
            $loan->update([
                'status' => $totalIssued >= $totalApproved
                    ? 'borrowed'
                    : 'partially_issued',
            ]);

            // Catat aktivitas penyerahan.
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action_type' => 'loan_issued',
                'description' => "Penyerahan {$totalIssuedNow} barang untuk pengajuan {$loan->loan_code}. Total diserahkan: {$totalIssued} dari {$totalApproved} barang.",
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with('success', 'Penyerahan barang berhasil dicatat.');
    }
}