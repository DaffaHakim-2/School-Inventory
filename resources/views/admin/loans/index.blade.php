@extends('layouts.admin')

@section('title', 'Pengelolaan Peminjaman')
@section('page-heading', 'Pengelolaan Peminjaman')

@section('content')

<!-- Header Halaman -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-2">
                            <i class="bi bi-clipboard-check text-primary me-2"></i>
                            Pengelolaan Peminjaman
                        </h4>

                        <p class="text-body-secondary mb-0">
                            Kelola, periksa, dan konfirmasi pengajuan
                            peminjaman barang dari User.
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <span class="badge text-bg-primary fs-6 px-3 py-2">
                            <i class="bi bi-clipboard-data me-1"></i>
                            Total: {{ $loans->total() }} Pengajuan
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    {{ session('error') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@php
$statusLabels = [
'pending' => 'Menunggu Konfirmasi',
'approved' => 'Disetujui',
'rejected' => 'Ditolak',
'partially_issued' => 'Sebagian Diserahkan',
'borrowed' => 'Sedang Dipinjam',
'partially_returned' => 'Sebagian Dikembalikan',
'returned' => 'Sudah Dikembalikan',
'cancelled' => 'Dibatalkan',
];

$statusColors = [
'pending' => 'warning',
'approved' => 'primary',
'rejected' => 'danger',
'partially_issued' => 'info',
'borrowed' => 'success',
'partially_returned' => 'info',
'returned' => 'secondary',
'cancelled' => 'dark',
];
@endphp

<!-- Tabel Pengajuan -->
<div class="card shadow-sm">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold mb-1">
                    <i class="bi bi-list-ul me-2 text-primary"></i>
                    Daftar Pengajuan Peminjaman
                </h5>

                <small class="text-body-secondary">
                    Informasi seluruh pengajuan peminjaman barang.
                </small>
            </div>

            <span class="badge text-bg-secondary">
                <i class="bi bi-table me-1"></i>
                Data Peminjaman
            </span>
        </div>
    </div>

    <div class="card-body p-0">

        @if ($loans->isEmpty())

        <div class="text-center py-5">
            <i class="bi bi-inbox display-3 text-body-secondary"></i>

            <h5 class="mt-3 fw-semibold">
                Belum Ada Pengajuan
            </h5>

            <p class="text-body-secondary mb-0">
                Saat ini belum ada pengajuan peminjaman dari User.
            </p>
        </div>

        @else

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th class="text-center px-3" width="60">No.</th>
                        <th>Kode Peminjaman</th>
                        <th>Nama User</th>
                        <th>Tanggal Peminjaman</th>
                        <th class="text-center">Jenis Barang</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($loans as $index => $loan)
                    <tr>
                        <td class="text-center">
                            {{ $loans->firstItem() + $index }}
                        </td>

                        <td>
                            <span class="fw-semibold text-primary">
                                {{ $loan->loan_code }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10
                                                text-primary d-flex align-items-center
                                                justify-content-center me-2" style="width: 35px; height: 35px;">

                                    <i class="bi bi-person"></i>
                                </div>

                                <span class="fw-medium">
                                    {{ $loan->user->name }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <i class="bi bi-calendar3 text-body-secondary me-1"></i>
                            {{ $loan->loan_date->format('d-m-Y') }}
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-secondary">
                                {{ $loan->details_count }} jenis
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-{{ $statusColors[$loan->status] ?? 'secondary' }} px-3 py-2">
                                {{ $statusLabels[$loan->status] ?? $loan->status }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-eye me-1"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @endif

    </div>

    @if ($loans->hasPages())
    <div class="card-footer">
        {{ $loans->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@endsection