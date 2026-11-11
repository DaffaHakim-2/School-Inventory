@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-heading', 'Detail Pengguna')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Detail Pengguna</h4>
            <p class="text-muted mb-0">
                Informasi akun dan ringkasan aktivitas pengguna.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">

        {{-- Informasi Akun --}}
        <div class="col-lg-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Akun
                    </h5>
                </div>

                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex
                                    align-items-center justify-content-center"
                            style="width: 90px; height: 90px; font-size: 36px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>

                    <h4 class="mb-1">{{ $user->name }}</h4>

                    @if($user->role === 'admin')
                    <span class="badge text-bg-primary">Admin</span>
                    @else
                    <span class="badge text-bg-success">User</span>
                    @endif

                    <hr>

                    <div class="text-start">
                        <div class="mb-3">
                            <small class="text-muted d-block">Nama Lengkap</small>
                            <strong>{{ $user->name }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Alamat Email</small>
                            <strong>{{ $user->email }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Hak Akses</small>
                            <strong>{{ ucfirst($user->role) }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal Registrasi</small>
                            <strong>
                                {{ $user->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                            </strong>
                        </div>

                        <div>
                            <small class="text-muted d-block">Terakhir Diperbarui</small>
                            <strong>
                                {{ $user->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning flex-fill">
                        <i class="bi bi-pencil-square me-1"></i>
                        Edit Pengguna
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        Tutup
                    </a>
                </div>
            </div>
        </div>

        {{-- Ringkasan Aktivitas --}}
        <div class="col-lg-7">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Ringkasan Aktivitas
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon text-bg-primary">
                                    <i class="bi bi-clipboard-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengajuan Peminjaman</span>
                                    <span class="info-box-number">
                                        {{ $user->loans_count }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon text-bg-success">
                                    <i class="bi bi-check-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Peminjaman Disetujui</span>
                                    <span class="info-box-number">
                                        {{ $user->approved_loans_count }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon text-bg-warning">
                                    <i class="bi bi-box-arrow-in-down"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengembalian Diterima</span>
                                    <span class="info-box-number">
                                        {{ $user->received_returns_count }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon text-bg-info">
                                    <i class="bi bi-arrow-left-right"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pergerakan Stok</span>
                                    <span class="info-box-number">
                                        {{ $user->item_movements_count }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon text-bg-secondary">
                                    <i class="bi bi-clock-history"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Catatan Aktivitas</span>
                                    <span class="info-box-number">
                                        {{ $user->activity_logs_count }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="alert alert-light border mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Ringkasan di atas dihitung berdasarkan riwayat transaksi
                        dan aktivitas yang tercatat di dalam sistem.
                    </div>
                </div>
            </div>

            {{-- Reset Password --}}
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Pengaturan Keamanan
                    </h5>
                </div>

                <div class="card-body">
                    <p class="text-muted">
                        Admin dapat mengatur ulang password pengguna apabila
                        pengguna mengalami kendala saat masuk ke sistem.
                    </p>

                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#resetPasswordModal">
                        <i class="bi bi-key me-1"></i>
                        Reset Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reset Password --}}
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel"
        aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="js-confirm-action"
                    data-confirm-title="Reset Password?"
                    data-confirm-text="Password pengguna akan diganti dengan password baru."
                    data-confirm-button="Ya, Reset Password" data-confirm-icon="warning">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel">
                            <i class="bi bi-key me-2"></i>
                            Reset Password Pengguna
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-light border">
                            Password baru untuk:
                            <strong>{{ $user->name }}</strong>
                            <br>
                            <small>{{ $user->email }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru <span class="text-danger">*</span>
                            </label>

                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" minlength="8"
                                autocomplete="new-password" required>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru
                                <span class="text-danger">*</span>
                            </label>

                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" minlength="8" autocomplete="new-password" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i>
                            Reset Password
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
@endsection