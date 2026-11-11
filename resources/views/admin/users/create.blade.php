@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-heading', 'Tambah Pengguna')

@section('content')
<div class="container-fluid">

    {{-- Header halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Tambah Pengguna</h4>
            <p class="text-muted mb-0">
                Tambahkan akun baru untuk mengakses sistem inventaris sekolah.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Informasi Pengguna
                    </h5>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap" maxlength="255" required autofocus>

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Alamat Email <span class="text-danger">*</span>
                            </label>

                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                placeholder="contoh@email.com" maxlength="255" required>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label for="role" class="form-label">
                                Hak Akses <span class="text-danger">*</span>
                            </label>

                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Hak Akses --</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                            </select>

                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Admin memiliki akses pengelolaan sistem, sedangkan User
                                dapat mengajukan dan melihat riwayat peminjaman.
                            </small>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">
                            <i class="bi bi-shield-lock me-2"></i>
                            Keamanan Akun
                        </h6>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>

                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter" minlength="8" autocomplete="new-password" required>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Password harus memiliki minimal 8 karakter.
                            </small>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>

                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Masukkan ulang password" minlength="8"
                                autocomplete="new-password" required>
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Informasi --}}
        <div class="col-lg-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi Hak Akses
                    </h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge text-bg-primary mb-2">Admin</span>
                        <p class="text-muted mb-0">
                            Dapat mengelola kategori, barang, pengguna, peminjaman,
                            pengembalian, dan aktivitas sistem.
                        </p>
                    </div>

                    <hr>

                    <div>
                        <span class="badge text-bg-success mb-2">User</span>
                        <p class="text-muted mb-0">
                            Dapat melihat barang yang tersedia, mengajukan peminjaman,
                            dan melihat riwayat peminjaman miliknya.
                        </p>
                    </div>
                </div>
            </div>

            <div class="alert alert-light border">
                <i class="bi bi-lightbulb me-2"></i>
                <strong>Catatan:</strong>
                Password akan disimpan dalam bentuk hash sehingga tidak dapat
                dibaca kembali oleh administrator.
            </div>
        </div>
    </div>
</div>
@endsection