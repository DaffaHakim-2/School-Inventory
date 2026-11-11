@extends('layouts.admin')

@section('title', 'Pengembalian Barang')
@section('page-heading', 'Daftar Pengembalian')

@section('content')

{{-- Informasi halaman --}}
<div class="mb-4">
    <p class="text-secondary mb-0">
        Kelola pengembalian barang dari transaksi peminjaman yang masih berlangsung.
    </p>
</div>

{{-- Notifikasi --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ session('error') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Ringkasan jumlah transaksi --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-primary-subtle text-primary p-3 me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>

                <div>
                    <div class="text-secondary small">
                        Transaksi Menunggu Pengembalian
                    </div>

                    <h4 class="mb-0 fw-bold">
                        {{ $loans->total() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-warning-subtle text-warning p-3 me-3">
                    <i class="bi bi-arrow-return-left fs-3"></i>
                </div>

                <div>
                    <div class="text-secondary small">
                        Halaman Saat Ini
                    </div>

                    <h4 class="mb-0 fw-bold">
                        {{ $loans->currentPage() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel pengembalian --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-body py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <h5 class="card-title fw-semibold mb-1">
                    <i class="bi bi-arrow-return-left me-2"></i>
                    Transaksi Pengembalian
                </h5>

                <small class="text-secondary">
                    Daftar peminjaman yang masih memiliki barang belum dikembalikan.
                </small>
            </div>

            {{-- Pencarian --}}
            <form action="{{ route('admin.returns.index') }}" method="GET" class="d-flex gap-2"
                style="max-width: 450px; width: 100%;">

                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode peminjaman..."
                    value="{{ $search }}">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>

                @if ($search)
                <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary" title="Reset pencarian">

                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </form>

        </div>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 60px;">No.</th>
                        <th>Kode Peminjaman</th>
                        <th>Peminjam</th>
                        <th class="text-center">Diserahkan</th>
                        <th class="text-center">Dikembalikan</th>
                        <th class="text-center">Sisa</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($loans as $loan)

                    @php
                    $totalIssued = $loan->details->sum('quantity_issued');

                    $totalReturned = $loan->details->sum(function ($detail) {
                    return $detail->returnDetails->sum('quantity_returned');
                    });

                    $remaining = max(0, $totalIssued - $totalReturned);
                    @endphp

                    <tr>
                        <td class="ps-3">
                            {{ $loans->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ $loan->loan_code }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $loan->user->name ?? 'Pengguna tidak ditemukan' }}
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-primary rounded-pill">
                                {{ $totalIssued }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-success rounded-pill">
                                {{ $totalReturned }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-warning rounded-pill">
                                {{ $remaining }}
                            </span>
                        </td>

                        <td>
                            @if ($loan->status === 'borrowed')
                            <span class="badge text-bg-primary">
                                Dipinjam
                            </span>
                            @elseif ($loan->status === 'partially_returned')
                            <span class="badge text-bg-warning">
                                Sebagian Dikembalikan
                            </span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-outline-primary"
                                title="Proses Pengembalian">

                                <i class="bi bi-arrow-return-left me-1"></i>
                                Proses
                            </a>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center py-5">

                            <div class="mb-3">
                                <i class="bi bi-inbox display-4 text-secondary"></i>
                            </div>

                            <h6 class="fw-semibold">
                                @if ($search)
                                Data tidak ditemukan
                                @else
                                Belum Ada Pengembalian
                                @endif
                            </h6>

                            <p class="text-secondary mb-0">
                                @if ($search)
                                Tidak ada peminjaman yang cocok dengan pencarian "{{ $search }}".
                                @else
                                Belum ada peminjaman yang menunggu pengembalian barang.
                                @endif
                            </p>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    @if ($loans->hasPages() || $loans->total() > 0)
    <div class="card-footer bg-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

        <small class="text-secondary">
            Menampilkan {{ $loans->firstItem() ?? 0 }}
            sampai {{ $loans->lastItem() ?? 0 }}
            dari {{ $loans->total() }} transaksi
        </small>

        {{ $loans->links('pagination::bootstrap-5') }}

    </div>
    @endif

</div>

@endsection