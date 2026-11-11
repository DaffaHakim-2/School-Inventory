@extends('layouts.user')

@section('title', 'Dashboard User')

@section('page-heading', 'Dashboard User')

@section('content')

<!-- Sambutan -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-1">
                    Selamat datang, {{ auth()->user()->name }}!
                </h5>

                <p class="text-secondary mb-0">
                    Selamat datang di Sistem Informasi Inventaris Sekolah.
                    Silakan gunakan menu untuk mengajukan peminjaman
                    dan melihat riwayat peminjaman barang.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Kartu Statistik -->
<div class="row">

    <!-- Total Peminjaman -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $totalLoans }}</h3>
                <p>Total Peminjaman</p>
            </div>

            <div class="small-box-icon">
                <i class="bi bi-clipboard-data"></i>
            </div>

            <a href="{{ route('user.loans.index') }}" class="small-box-footer link-light link-underline-opacity-0">
                Lihat Riwayat
                <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <!-- Menunggu Persetujuan -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ $pendingLoans }}</h3>
                <p>Menunggu Persetujuan</p>
            </div>

            <div class="small-box-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <a href="{{ route('user.loans.index') }}" class="small-box-footer link-dark link-underline-opacity-0">
                Lihat Riwayat
                <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <!-- Sedang Dipinjam -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $activeLoans }}</h3>
                <p>Sedang Dipinjam</p>
            </div>

            <div class="small-box-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <a href="{{ route('user.loans.index') }}" class="small-box-footer link-light link-underline-opacity-0">
                Lihat Riwayat
                <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

    <!-- Sudah Dikembalikan -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $returnedLoans }}</h3>
                <p>Sudah Dikembalikan</p>
            </div>

            <div class="small-box-icon">
                <i class="bi bi-check-circle"></i>
            </div>

            <a href="{{ route('user.loans.index') }}" class="small-box-footer link-light link-underline-opacity-0">
                Lihat Riwayat
                <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>

</div>

<!-- Riwayat Peminjaman Terbaru -->
<div class="row">
    <div class="col-12">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-clock-history me-1"></i>
                    Riwayat Peminjaman Terbaru
                </h3>

                <div class="card-tools">
                    <a href="{{ route('user.loans.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
            </div>

            <div class="card-body table-responsive p-0">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Peminjaman</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Jumlah Jenis Barang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($latestLoans as $loan)

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
                        'approved' => 'primary',
                        'rejected' => 'danger',
                        'partially_issued' => 'info',
                        'borrowed' => 'success',
                        'partially_returned' => 'info',
                        'returned' => 'secondary',
                        'cancelled' => 'dark',
                        ];
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $loan->loan_code }}</strong>
                            </td>

                            <td>
                                {{ $loan->loan_date->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $loan->details_count }} jenis barang
                            </td>

                            <td>
                                <span class="badge text-bg-{{ $statusColors[$loan->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$loan->status] ?? $loan->status }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('user.loans.show', $loan) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox fs-2 text-secondary"></i>

                                <p class="text-secondary mb-0 mt-2">
                                    Belum ada riwayat peminjaman.
                                </p>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="card-footer clearfix">
                <a href="{{ route('user.loans.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Ajukan Peminjaman
                </a>
            </div>

        </div>

    </div>
</div>

@endsection