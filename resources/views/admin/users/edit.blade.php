@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-heading', 'Edit Pengguna')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Pengguna</h4>
            <p class="text-muted mb-0">
                Perbarui informasi akun pengguna.
            </p>
        </div>

        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row">

        {{-- Form Edit --}}
        <div class="col-lg-8">
            <div class="card card-warning card-outline">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Form Edit Pengguna
                    </h5>
                </div>

                <form action="{{ route('admin.users.update', $user) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" maxlength="255" required>

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
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" maxlength="255" required>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label for="role" class="form-label">
                                Hak Akses <span class="text-danger">*</span>
                            </label>

                            @if($user->id === auth()->id())

                            {{-- Role akun sendiri tidak boleh diubah --}}
                            <input type="hidden" name="role" value="{{ $user->role }}">

                            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>

                            <small class="form-text text-muted">
                                Role akun yang sedang digunakan tidak dapat diubah.
                            </small>

                            @else

                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror"
                                required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                            </select>

                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @endif
                        </div>

                        <div class="alert alert-light border mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Perubahan informasi pengguna akan dicatat dalam
                            Activity Log.
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Informasi Akun --}}
        <div class="col-lg-4">
            <div class="card card-info card-outline">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Akun
                    </h5>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Pengguna</small>
                        <strong>{{ $user->name }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Email Saat Ini</small>
                        <strong>{{ $user->email }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Role Saat Ini</small>

                        @if($user->role === 'admin')
                        <span class="badge text-bg-primary">Admin</span>
                        @else
                        <span class="badge text-bg-success">User</span>
                        @endif
                    </div>

                    <div>
                        <small class="text-muted d-block">Terdaftar Sejak</small>
                        <strong>
                            {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                        </strong>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong>
                Perubahan role dapat memengaruhi hak akses pengguna
                ketika menggunakan sistem.
            </div>
        </div>

    </div>
</div>
@endsection