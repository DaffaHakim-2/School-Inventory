@extends('layouts.admin')

@section('title', 'Activity Log')

@section('page-heading')
<div>
    <h1 class="mb-1">Activity Log</h1>
    <p class="text-muted mb-0">
        Riwayat aktivitas yang dilakukan dalam sistem inventaris sekolah.
    </p>
</div>
@endsection

@section('content')

@php
$actionLabels = [
'loan_approved' => 'Peminjaman Disetujui',
'loan_rejected' => 'Peminjaman Ditolak',
'loan_issued' => 'Barang Diserahkan',
'loan_returned' => 'Pengembalian Barang',
'category_created' => 'Kategori Ditambahkan',
'item_created' => 'Barang Ditambahkan',

// Aktivitas Manajemen Pengguna
'user_created' => 'Pengguna Ditambahkan',
'user_updated' => 'Data Pengguna Diubah',
'user_password_reset' => 'Password Diatur Ulang',
'user_deleted' => 'Pengguna Dihapus',
];

$actionColors = [
'loan_approved' => 'success',
'loan_rejected' => 'danger',
'loan_issued' => 'primary',
'loan_returned' => 'info',
'category_created' => 'secondary',
'item_created' => 'primary',

// Aktivitas Manajemen Pengguna
'user_created' => 'success',
'user_updated' => 'warning',
'user_password_reset' => 'info',
'user_deleted' => 'danger',
];

$actionIcons = [
'loan_approved' => 'bi-check-circle',
'loan_rejected' => 'bi-x-circle',
'loan_issued' => 'bi-box-arrow-up-right',
'loan_returned' => 'bi-box-arrow-in-down',
'category_created' => 'bi-tags',
'item_created' => 'bi-box-seam',

// Aktivitas Manajemen Pengguna
'user_created' => 'bi-person-plus',
'user_updated' => 'bi-pencil-square',
'user_password_reset' => 'bi-key',
'user_deleted' => 'bi-person-x',
];
@endphp
{{-- Ringkasan --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $activityLogs->total() }}</h3>
                <p>Total Aktivitas Ditemukan</p>
            </div>
            <div class="icon">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Aktivitas --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-list-ul me-1"></i>
            Riwayat Aktivitas
        </h3>
    </div>

    <div class="card-body">

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="mb-4">

            <div class="row g-2">

                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama pengguna atau deskripsi..." value="{{ $search }}">
                </div>

                <div class="col-md-4">
                    <select name="action_type" class="form-select">
                        <option value="">Semua Jenis Aktivitas</option>

                        @foreach ($actionLabels as $value => $label)
                        <option value="{{ $value }}" {{ $actionType === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>

                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Tabel Aktivitas --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <thead>
                    <tr>
                        <th width="60">No.</th>
                        <th>Pengguna</th>
                        <th>Jenis Aktivitas</th>
                        <th>Deskripsi</th>
                        <th width="180">Waktu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($activityLogs as $log)
                    <tr>
                        <td>
                            {{ $activityLogs->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle fs-5 text-secondary"></i>

                                <span>
                                    {{ $log->user?->name ?? 'Sistem' }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-{{ $actionColors[$log->action_type] ?? 'secondary' }}">
                                <i class="bi {{ $actionIcons[$log->action_type] ?? 'bi-activity' }} me-1"></i>

                                {{ $actionLabels[$log->action_type] ?? $log->action_type }}
                            </span>
                        </td>

                        <td>
                            {{ $log->description }}
                        </td>

                        <td>
                            <i class="bi bi-calendar-event me-1 text-secondary"></i>
                            {{ $log->created_at?->format('d/m/Y') }}

                            <br>

                            <i class="bi bi-clock me-1 text-secondary"></i>
                            {{ $log->created_at?->format('H:i:s') }}
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-secondary"></i>

                            <p class="mt-2 mb-1 fw-semibold">
                                Tidak ada aktivitas ditemukan.
                            </p>

                            <small class="text-muted">
                                Coba ubah kata pencarian atau filter aktivitas.
                            </small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($activityLogs->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

            <small class="text-muted">
                Menampilkan
                {{ $activityLogs->firstItem() ?? 0 }}
                sampai
                {{ $activityLogs->lastItem() ?? 0 }}
                dari
                {{ $activityLogs->total() }}
                aktivitas.
            </small>

            {{ $activityLogs->links('pagination::bootstrap-5') }}

        </div>
        @endif

    </div>
</div>

@endsection