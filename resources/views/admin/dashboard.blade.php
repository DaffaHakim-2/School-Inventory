@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-heading', 'Dashboard Admin')

@section('content')

<!-- Sambutan -->
<div class="mb-4">
    <h5>Selamat datang, {{ auth()->user()->name }}!</h5>
    <p class="text-secondary">
        Berikut ringkasan informasi inventaris sekolah saat ini.
    </p>
</div>

<!-- Kartu Statistik -->
<div class="row">

    <!-- Total Kategori -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">

            <div class="inner">
                <h3>{{ number_format($totalCategories) }}</h3>
                <p>Total Kategori</p>
            </div>

            <i class="small-box-icon bi bi-tags"></i>

            <a href="{{ route('admin.categories.index') }}"
                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Lihat Kategori
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>
    </div>

    <!-- Total Barang -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">

            <div class="inner">
                <h3>{{ number_format($totalItems) }}</h3>
                <p>Total Jenis Barang</p>
            </div>

            <i class="small-box-icon bi bi-box-seam"></i>

            <a href="{{ route('admin.items.index') }}"
                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                Lihat Barang
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>
    </div>

    <!-- Total Stok -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">

            <div class="inner">
                <h3>{{ number_format($totalStock) }}</h3>
                <p>Total Stok Barang</p>
            </div>

            <i class="small-box-icon bi bi-boxes"></i>

            <a href="{{ route('admin.items.index') }}"
                class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                Lihat Stok
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>
    </div>

    <!-- Peminjaman Pending -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">

            <div class="inner">
                <h3>{{ number_format($pendingLoans) }}</h3>
                <p>Menunggu Persetujuan</p>
            </div>

            <i class="small-box-icon bi bi-clock-history"></i>

            <a href="{{ route('admin.loans.index') }}"
                class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                Lihat Pengajuan
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>
    </div>

</div>

<!-- Peminjaman Terbaru -->
<div class="card mt-4">

    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-clock-history me-2"></i>
            Peminjaman Terbaru
        </h3>

        <div class="card-tools">
            <a href="{{ route('admin.loans.index') }}" class="btn btn-sm btn-primary">
                Lihat Semua
            </a>
        </div>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Kode Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Jumlah Jenis Barang</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($latestLoans as $loan)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $loan->loan_code }}</strong>
                        </td>

                        <td>
                            {{ $loan->user->name }}
                        </td>

                        <td>
                            {{ $loan->details_count }} jenis
                        </td>

                        <td>
                            {{ $loan->loan_date->format('d-m-Y') }}
                        </td>

                        <td>
                            @php
                            $statusClasses = [
                            'pending' => 'bg-warning text-dark',
                            'approved' => 'bg-primary',
                            'rejected' => 'bg-danger',
                            'partially_issued' => 'bg-info text-dark',
                            'borrowed' => 'bg-success',
                            'partially_returned' => 'bg-info text-dark',
                            'returned' => 'bg-secondary',
                            'cancelled' => 'bg-dark',
                            ];

                            $statusLabels = [
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'partially_issued' => 'Diserahkan Sebagian',
                            'borrowed' => 'Dipinjam',
                            'partially_returned' => 'Dikembalikan Sebagian',
                            'returned' => 'Dikembalikan',
                            'cancelled' => 'Dibatalkan',
                            ];
                            @endphp

                            <span class="badge {{ $statusClasses[$loan->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$loan->status] ?? $loan->status }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                                Detail
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-2 text-secondary"></i>
                            <p class="mb-0 mt-2">
                                Belum ada data peminjaman.
                            </p>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection