@extends('layouts.admin')

@section('title', 'Detail Peminjaman')

@section('page-heading')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="h3 mb-1">Detail Peminjaman</h1>
        <p class="text-body-secondary mb-0">
            Informasi dan pengelolaan transaksi peminjaman barang.
        </p>
    </div>

    <a href="{{ route('admin.loans.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')

@php
$statusLabels = [
'pending' => 'Menunggu Persetujuan',
'approved' => 'Disetujui',
'rejected' => 'Ditolak',
'partially_issued' => 'Diserahkan Sebagian',
'borrowed' => 'Sedang Dipinjam',
'partially_returned' => 'Dikembalikan Sebagian',
'returned' => 'Sudah Dikembalikan',
'cancelled' => 'Dibatalkan',
];

$statusColors = [
'pending' => 'warning',
'approved' => 'success',
'rejected' => 'danger',
'partially_issued' => 'info',
'borrowed' => 'primary',
'partially_returned' => 'info',
'returned' => 'success',
'cancelled' => 'secondary',
];

$statusColor = $statusColors[$loan->status] ?? 'secondary';
$statusLabel = $statusLabels[$loan->status] ?? ucfirst(str_replace('_', ' ', $loan->status));

$returnableDetails = $loan->details->filter(function ($detail) {
$returnedQuantity = $detail->returnDetails->sum('quantity_returned');

return $detail->quantity_issued > $returnedQuantity;
});

$totalRequested = $loan->details->sum('quantity_requested');
$totalApproved = $loan->details->sum('quantity_approved');
$totalIssued = $loan->details->sum('quantity_issued');

$totalReturned = $loan->details->sum(function ($detail) {
return $detail->returnDetails->sum('quantity_returned');
});
@endphp

{{-- Notifikasi Berhasil --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Notifikasi Error --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="fw-semibold mb-2">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Proses tidak dapat dilakukan:
    </div>

    <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Ringkasan Jumlah Barang --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-body-secondary small">Diajukan</div>
                        <div class="fs-3 fw-semibold">{{ $totalRequested }}</div>
                    </div>
                    <i class="bi bi-box-seam fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-body-secondary small">Disetujui</div>
                        <div class="fs-3 fw-semibold">{{ $totalApproved }}</div>
                    </div>
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-body-secondary small">Diserahkan</div>
                        <div class="fs-3 fw-semibold">{{ $totalIssued }}</div>
                    </div>
                    <i class="bi bi-box-arrow-up-right fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-body-secondary small">Dikembalikan</div>
                        <div class="fs-3 fw-semibold">{{ $totalReturned }}</div>
                    </div>
                    <i class="bi bi-arrow-return-left fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Informasi Peminjaman --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-clipboard-data me-2 text-primary"></i>
            Informasi Peminjaman
        </h5>
    </div>

    <div class="card-body">
        <div class="row g-4">

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Kode Peminjaman</div>
                <div class="fw-semibold">{{ $loan->loan_code }}</div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Nama Peminjam</div>
                <div class="fw-semibold">{{ $loan->user->name }}</div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Status Peminjaman</div>
                <span class="badge text-bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Tanggal Peminjaman</div>
                <div class="fw-medium">
                    {{ $loan->loan_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Rencana Pengembalian</div>
                <div class="fw-medium">
                    {{ $loan->planned_return_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="text-body-secondary small mb-1">Disetujui Oleh</div>
                <div class="fw-medium">
                    {{ $loan->approver?->name ?? '-' }}
                </div>
            </div>

            <div class="col-12">
                <div class="text-body-secondary small mb-1">Keperluan</div>
                <div class="fw-medium">{{ $loan->purpose ?? '-' }}</div>
            </div>

        </div>
    </div>
</div>

{{-- Rincian Barang --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-box-seam me-2 text-primary"></i>
            Rincian Barang yang Diajukan
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No.</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok Saat Ini</th>
                        <th class="text-center">Diajukan</th>
                        <th class="text-center">Disetujui</th>
                        <th class="text-center">Diserahkan</th>
                        <th class="text-center">Dikembalikan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($loan->details as $index => $detail)
                    <tr>
                        <td class="ps-3">{{ $index + 1 }}</td>

                        <td class="fw-medium">
                            {{ $detail->item?->name ?? 'Barang tidak tersedia' }}
                        </td>

                        <td>
                            {{ $detail->item?->category?->name ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $detail->item?->stock ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $detail->quantity_requested }}
                        </td>

                        <td class="text-center">
                            {{ $detail->quantity_approved }}
                        </td>

                        <td class="text-center">
                            {{ $detail->quantity_issued }}
                        </td>

                        <td class="text-center">
                            {{ $detail->returnDetails->sum('quantity_returned') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-body-secondary py-4">
                            Belum ada rincian barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

{{-- Riwayat Pengembalian --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2 text-success"></i>
            Riwayat Pengembalian
        </h5>
    </div>

    <div class="card-body">

        @forelse ($returns as $return)

        <div class="border rounded-3 p-3 mb-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <div class="text-body-secondary small">Kode Pengembalian</div>
                    <div class="fw-semibold">{{ $return->return_code }}</div>
                </div>

                <span class="badge text-bg-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Transaksi Tercatat
                </span>
            </div>

            <div class="row g-3 mb-3">

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="text-body-secondary small">Tanggal Pengembalian</div>
                    <div class="fw-medium">
                        {{ $return->return_date?->format('d-m-Y') ?? '-' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="text-body-secondary small">Diterima Oleh</div>
                    <div class="fw-medium">
                        {{ $return->receiver?->name ?? '-' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-6">
                    <div class="text-body-secondary small">Catatan Pengembalian</div>
                    <div class="fw-medium">{{ $return->notes ?? '-' }}</div>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Kondisi</th>
                            <th>Catatan Kondisi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($return->details as $index => $returnDetail)
                        @php
                        $conditionLabels = [
                        'good' => 'Baik',
                        'damaged' => 'Rusak',
                        'lost' => 'Hilang',
                        ];

                        $conditionColors = [
                        'good' => 'success',
                        'damaged' => 'warning',
                        'lost' => 'danger',
                        ];

                        $condition = $returnDetail->condition;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="fw-medium">
                                {{ $returnDetail->loanDetail?->item?->name ?? 'Barang tidak tersedia' }}
                            </td>

                            <td class="text-center">
                                {{ $returnDetail->quantity_returned }}
                            </td>

                            <td class="text-center">
                                <span class="badge text-bg-{{ $conditionColors[$condition] ?? 'secondary' }}">
                                    {{ $conditionLabels[$condition] ?? ucfirst($condition) }}
                                </span>
                            </td>

                            <td>{{ $returnDetail->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        @empty

        <div class="text-center text-body-secondary py-4">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            Belum ada transaksi pengembalian untuk peminjaman ini.
        </div>

        @endforelse

    </div>
</div>

{{-- Persetujuan Pengajuan --}}
@if ($loan->status === 'pending')

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-check2-square me-2 text-success"></i>
            Proses Persetujuan
        </h5>
    </div>

    <div class="card-body">

        <div class="alert alert-info">
            Tentukan jumlah setiap barang yang akan disetujui.
            Jumlah tidak boleh melebihi jumlah yang diajukan.
            Masukkan 0 jika barang tidak disetujui.
        </div>

        <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="js-confirm-action"
            data-confirm-title="Setujui Pengajuan?"
            data-confirm-text="Pastikan jumlah barang yang disetujui sudah benar." data-confirm-button="Ya, Setujui"
            data-confirm-icon="question" data-confirm-color="#198754">
            @csrf

            <div class="row g-3">

                @foreach ($loan->details as $index => $detail)

                <div class="col-12 col-xl-6">
                    <div class="border rounded-3 p-3 h-100">

                        <input type="hidden" name="items[{{ $index }}][loan_detail_id]" value="{{ $detail->id }}">

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $detail->item?->name ?? 'Barang tidak tersedia' }}
                                </div>

                                <small class="text-body-secondary">
                                    Stok saat ini:
                                    {{ $detail->item?->stock ?? '-' }}
                                </small>
                            </div>

                            <span class="badge text-bg-primary">
                                Diajukan: {{ $detail->quantity_requested }}
                            </span>
                        </div>

                        <label class="form-label fw-semibold">
                            Jumlah Disetujui
                        </label>

                        <input type="number" name="items[{{ $index }}][quantity_approved]"
                            value="{{ old("items.$index.quantity_approved", $detail->quantity_requested) }}" min="0"
                            max="{{ $detail->quantity_requested }}" class="form-control" required>

                    </div>
                </div>

                @endforeach

            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Proses Persetujuan
                </button>
            </div>

        </form>

    </div>
</div>

{{-- Penolakan Pengajuan --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-x-circle me-2 text-danger"></i>
            Tolak Pengajuan
        </h5>
    </div>

    <div class="card-body">

        <p class="text-body-secondary">
            Gunakan formulir ini jika pengajuan peminjaman harus ditolak.
        </p>

        <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="js-confirm-action"
            data-confirm-title="Tolak Pengajuan?" data-confirm-text="Pengajuan peminjaman ini akan ditolak."
            data-confirm-button="Ya, Tolak" data-confirm-icon="warning" data-confirm-color="#dc3545">
            @csrf

            <div class="mb-3">
                <label for="rejection_reason" class="form-label fw-semibold">
                    Alasan Penolakan
                    <span class="text-danger">*</span>
                </label>

                <textarea id="rejection_reason" name="rejection_reason" rows="3" minlength="5" maxlength="1000"
                    class="form-control @error('rejection_reason') is-invalid @enderror"
                    placeholder="Masukkan alasan penolakan pengajuan..."
                    required>{{ old('rejection_reason') }}</textarea>

                @error('rejection_reason')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle me-1"></i>
                    Tolak Pengajuan
                </button>
            </div>

        </form>

    </div>
</div>

@endif

{{-- Penyerahan Barang --}}
@if (in_array($loan->status, ['approved', 'partially_issued']))

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-box-arrow-up-right me-2 text-primary"></i>
            Proses Penyerahan Barang
        </h5>
    </div>

    <div class="card-body">

        <div class="alert alert-info">
            Masukkan jumlah barang yang diserahkan pada transaksi ini.
            Penyerahan dapat dilakukan secara bertahap.
            Stok barang akan berkurang setelah penyerahan berhasil dicatat.
        </div>

        <form action="{{ route('admin.loans.issue', $loan) }}" method="POST" class="js-confirm-action"
            data-confirm-title="Catat Penyerahan Barang?"
            data-confirm-text="Stok barang akan dikurangi sesuai jumlah yang berhasil diserahkan."
            data-confirm-button="Ya, Catat Penyerahan" data-confirm-icon="question" data-confirm-color="#0d6efd">
            @csrf

            <div class="row g-3">

                @foreach ($loan->details as $index => $detail)

                @php
                $remainingQuantity = $detail->quantity_approved
                - $detail->quantity_issued;
                @endphp

                <div class="col-12 col-xl-6">
                    <div class="border rounded-3 p-3 h-100">

                        <input type="hidden" name="items[{{ $index }}][loan_detail_id]" value="{{ $detail->id }}">

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <div class="fw-semibold">
                                    {{ $detail->item?->name ?? 'Barang tidak tersedia' }}
                                </div>

                                <small class="text-body-secondary d-block">
                                    Disetujui: {{ $detail->quantity_approved }}
                                </small>

                                <small class="text-body-secondary d-block">
                                    Sudah diserahkan: {{ $detail->quantity_issued }}
                                </small>

                                <small class="text-body-secondary d-block">
                                    Stok tersedia: {{ $detail->item?->stock ?? '-' }}
                                </small>
                            </div>

                            <span class="badge text-bg-info">
                                Sisa: {{ $remainingQuantity }}
                            </span>
                        </div>

                        @if ($remainingQuantity > 0)

                        <label class="form-label fw-semibold">
                            Jumlah Diserahkan Sekarang
                        </label>

                        <input type="number" name="items[{{ $index }}][quantity_issued]"
                            value="{{ old("items.$index.quantity_issued", $remainingQuantity) }}" min="0"
                            max="{{ $remainingQuantity }}" class="form-control" required>

                        @else

                        <input type="hidden" name="items[{{ $index }}][quantity_issued]" value="0">

                        <div class="alert alert-light border mb-0 py-2">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Seluruh jumlah yang disetujui sudah diserahkan.
                        </div>

                        @endif

                    </div>
                </div>

                @endforeach

            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Catat Penyerahan Barang
                </button>
            </div>

        </form>

    </div>
</div>

@endif

{{-- Pengembalian Barang --}}
@if (in_array($loan->status, ['borrowed', 'partially_returned']))

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <h5 class="mb-0">
            <i class="bi bi-arrow-return-left me-2 text-success"></i>
            Proses Pengembalian Barang
        </h5>
    </div>

    <div class="card-body">

        <div class="alert alert-info">
            Catat barang yang diterima dari peminjam.
            Pengembalian dapat dilakukan secara bertahap.
            Hanya barang dengan kondisi baik yang akan menambah stok tersedia.
        </div>

        @if ($returnableDetails->isNotEmpty())
        <form action="{{ route('admin.loans.returns.store', $loan) }}" method="POST" class="js-confirm-action"
            data-confirm-title="Catat Pengembalian Barang?"
            data-confirm-text="Pastikan jumlah dan kondisi barang yang diterima sudah benar."
            data-confirm-button="Ya, Catat Pengembalian" data-confirm-icon="question" data-confirm-color="#198754">
            @csrf

            {{-- Tanggal Pengembalian --}}
            <div class="mb-4">
                <label for="return_date" class="form-label fw-semibold">
                    Tanggal Pengembalian
                    <span class="text-danger">*</span>
                </label>

                <input type="date" id="return_date" name="return_date"
                    value="{{ old('return_date', now()->toDateString()) }}"
                    class="form-control @error('return_date') is-invalid @enderror" style="max-width: 260px" required>

                @error('return_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Rincian Barang yang Dapat Dikembalikan --}}
            <div class="row g-3">

                @foreach ($returnableDetails as $index => $detail)

                @php
                $returnedQuantity = $detail->returnDetails->sum('quantity_returned');
                $remainingQuantity = $detail->quantity_issued - $returnedQuantity;
                @endphp

                <div class="col-12 col-xl-6">

                    <div class="border rounded-3 p-3 h-100">

                        <input type="hidden" name="items[{{ $index }}][loan_detail_id]" value="{{ $detail->id }}">

                        <div class="mb-3">
                            <div class="fw-semibold">
                                {{ $detail->item?->name ?? 'Barang tidak tersedia' }}
                            </div>

                            <div class="small text-body-secondary mt-2">
                                Jumlah diserahkan:
                                {{ $detail->quantity_issued }}
                            </div>

                            <div class="small text-body-secondary">
                                Sudah dikembalikan:
                                {{ $returnedQuantity }}
                            </div>

                            <div class="small fw-semibold text-success">
                                Sisa pengembalian:
                                {{ $remainingQuantity }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Jumlah Dikembalikan Sekarang
                            </label>

                            <input type="number" name="items[{{ $index }}][quantity_returned]"
                                value="{{ old("items.$index.quantity_returned", $remainingQuantity) }}" min="1"
                                max="{{ $remainingQuantity }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Kondisi Barang
                            </label>

                            <select name="items[{{ $index }}][condition]" class="form-select" required>
                                <option value="good"
                                    {{ old("items.$index.condition", 'good') === 'good' ? 'selected' : '' }}>
                                    Baik
                                </option>

                                <option value="damaged"
                                    {{ old("items.$index.condition") === 'damaged' ? 'selected' : '' }}>
                                    Rusak
                                </option>

                                <option value="lost" {{ old("items.$index.condition") === 'lost' ? 'selected' : '' }}>
                                    Hilang
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">
                                Catatan Kondisi
                            </label>

                            <textarea name="items[{{ $index }}][notes]" rows="2" maxlength="1000" class="form-control"
                                placeholder="Catatan kondisi barang (opsional)">{{ old("items.$index.notes") }}</textarea>
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

            {{-- Catatan Umum --}}
            <div class="mt-4">
                <label for="return_notes" class="form-label fw-semibold">
                    Catatan Pengembalian
                </label>

                <textarea id="return_notes" name="notes" rows="3" maxlength="1000"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="Catatan tambahan untuk transaksi pengembalian (opsional)">{{ old('notes') }}</textarea>

                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i>
                    Catat Pengembalian Barang
                </button>
            </div>

        </form>

        @else

        <div class="alert alert-success mb-0">
            <i class="bi bi-check-circle-fill me-2"></i>
            Semua barang yang telah diserahkan sudah dikembalikan.
        </div>

        @endif

    </div>
</div>

@endif

@endsection