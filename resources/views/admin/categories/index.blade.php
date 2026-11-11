@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-heading', 'Manajemen Kategori')

@section('content')

<!-- Header Halaman -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-2">
                            <i class="bi bi-tags text-primary me-2"></i>
                            Manajemen Kategori
                        </h4>

                        <p class="text-body-secondary mb-0">
                            Kelola kategori barang yang digunakan dalam
                            sistem inventaris sekolah.
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Tambah Kategori
                        </a>
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

<!-- Daftar Kategori -->
<div class="card shadow-sm">

    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold mb-1">
                    <i class="bi bi-list-ul text-primary me-2"></i>
                    Daftar Kategori
                </h5>

                <small class="text-body-secondary">
                    Informasi kategori barang dalam inventaris.
                </small>
            </div>

            <span class="badge text-bg-secondary">
                {{ $categories->total() }} Kategori
            </span>
        </div>
    </div>

    <div class="card-body">

        <!-- Form Pencarian -->
        <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-4">

            <div class="row g-2">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama kategori...">
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>
                </div>

                <div class="col-auto">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                </div>
            </div>

        </form>

        <!-- Tabel Kategori -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th class="text-center" width="60">No.</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Barang</th>
                        <th class="text-center" width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td class="text-center">
                            {{ $categories->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10
                                            text-primary d-flex align-items-center
                                            justify-content-center me-2" style="width: 36px; height: 36px;">

                                    <i class="bi bi-tag"></i>
                                </div>

                                <span class="fw-semibold">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <span class="text-body-secondary">
                                {{ $category->description ?: '-' }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge text-bg-info">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $category->items_count }} Barang
                            </span>
                        </td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit kategori">

                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    class="js-confirm-delete" data-confirm-title="Hapus Kategori?"
                                    data-confirm-text="Kategori {{ $category->name }} akan dihapus. Apakah kamu yakin?"
                                    data-confirm-button="Ya, Hapus Kategori">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus kategori">

                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">

                            <i class="bi bi-inbox display-4 text-body-secondary"></i>

                            <h6 class="fw-semibold mt-3">
                                Tidak Ada Kategori
                            </h6>

                            <p class="text-body-secondary mb-0">
                                @if (request('search'))
                                Kategori dengan kata kunci tersebut
                                tidak ditemukan.
                                @else
                                Belum ada kategori yang ditambahkan.
                                @endif
                            </p>

                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

    <!-- Pagination -->
    @if ($categories->hasPages())
    <div class="card-footer">
        {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@endsection