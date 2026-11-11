@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page-heading', 'Manajemen Pengguna')

@section('content')

{{-- Header halaman --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-2">
                            <i class="bi bi-people text-primary me-2"></i>
                            Manajemen Pengguna
                        </h4>

                        <p class="text-body-secondary mb-0">
                            Kelola akun Admin dan User yang terdaftar
                            dalam sistem inventaris sekolah.
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notifikasi --}}
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

@if ($errors->has('user'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    {{ $errors->first('user') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Ringkasan pengguna --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $users->total() }}</h3>
                <p>Total Pengguna</p>
            </div>
            <div class="icon">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                <p>Admin</p>
            </div>
            <div class="icon">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\User::where('role', 'user')->count() }}</h3>
                <p>User</p>
            </div>
            <div class="icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
    </div>
</div>

{{-- Daftar pengguna --}}
<div class="card shadow-sm">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold mb-1">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Daftar Pengguna
                </h5>

                <small class="text-body-secondary">
                    Informasi akun yang terdaftar dalam sistem.
                </small>
            </div>

            <span class="badge text-bg-secondary">
                {{ $users->total() }} Pengguna
            </span>
        </div>
    </div>

    <div class="card-body">

        {{-- Form pencarian dan filter --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4">

            <div class="row g-2">

                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama atau email pengguna...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>
                            User
                        </option>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>
                </div>

                <div class="col-auto">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- Tabel pengguna --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th class="text-center" width="60">No.</th>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Tanggal Registrasi</th>
                        <th class="text-center" width="220">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="text-center">
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10
                                    text-primary d-flex align-items-center
                                    justify-content-center me-2" style="width: 38px; height: 38px;">

                                    <i class="bi bi-person-fill"></i>
                                </div>

                                <span class="fw-semibold">
                                    {{ $user->name }}
                                </span>

                                @if ($user->id === auth()->id())
                                <span class="badge text-bg-secondary ms-2">
                                    Akun Saya
                                </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="text-body-secondary">
                                {{ $user->email }}
                            </span>
                        </td>

                        <td class="text-center">
                            @if ($user->role === 'admin')
                            <span class="badge text-bg-primary">
                                <i class="bi bi-shield-check me-1"></i>
                                Admin
                            </span>
                            @else
                            <span class="badge text-bg-info">
                                <i class="bi bi-person me-1"></i>
                                User
                            </span>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $user->created_at?->format('d/m/Y') ?? '-' }}
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info"
                                    title="Detail pengguna">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning"
                                    title="Edit pengguna">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    class="js-confirm-delete" data-confirm-title="Hapus Pengguna?"
                                    data-confirm-text="Akun {{ $user->name }} akan dihapus jika tidak memiliki riwayat transaksi atau aktivitas. Lanjutkan?"
                                    data-confirm-button="Ya, Hapus Pengguna">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus pengguna">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-people display-4 text-body-secondary"></i>

                            <h6 class="fw-semibold mt-3">
                                Tidak Ada Pengguna
                            </h6>

                            <p class="text-body-secondary mb-0">
                                @if (request('search') || request('role'))
                                Tidak ditemukan pengguna yang sesuai
                                dengan pencarian atau filter.
                                @else
                                Belum ada pengguna yang terdaftar.
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
    @if ($users->hasPages())
    <div class="card-footer">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@endsection