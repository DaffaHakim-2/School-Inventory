@extends('layouts.admin')

@section('title', 'Data Barang')

@section('page-heading')
<div>
    <h1 class="h3 mb-1">Manajemen Barang</h1>
    <p class="text-body-secondary mb-0">
        Kelola data barang inventaris sekolah.
    </p>
</div>
@endsection

@section('content')

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
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Daftar Barang --}}
<div class="card border-0 shadow-sm">

    {{-- Header Card --}}
    <div class="card-header bg-body border-bottom py-3">
        <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Daftar Barang
                </h5>
                <small class="text-body-secondary">
                    Berikut adalah seluruh data barang inventaris sekolah.
                </small>
            </div>

            <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Barang
            </a>

        </div>
    </div>

    <div class="card-body">

        {{-- Pencarian --}}
        <form action="{{ route('admin.items.index') }}" method="GET" class="mb-4">

            <div class="row g-2">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-body">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari kode atau nama barang...">
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>
                </div>

                @if (request('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>
                </div>
                @endif
            </div>

        </form>

        {{-- Informasi Jumlah Data --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-body-secondary">
                Menampilkan
                <strong>{{ $items->firstItem() ?? 0 }}</strong>
                sampai
                <strong>{{ $items->lastItem() ?? 0 }}</strong>
                dari
                <strong>{{ $items->total() }}</strong>
                barang
            </small>
        </div>

        {{-- Tabel Barang --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="65">No.</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center" width="210">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($items as $item)
                    <tr>
                        {{-- Nomor --}}
                        <td class="text-center text-body-secondary">
                            {{ $items->firstItem() + $loop->index }}
                        </td>

                        {{-- Kode Barang --}}
                        <td>
                            <span class="fw-semibold">
                                {{ $item->code }}
                            </span>
                        </td>

                        {{-- Nama Barang --}}
                        <td>
                            <div class="fw-medium">
                                {{ $item->name }}
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td>
                            <span class="text-body-secondary">
                                {{ $item->category->name ?? '-' }}
                            </span>
                        </td>

                        {{-- Stok --}}
                        <td class="text-center">
                            @if ($item->stock > 0)
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ $item->stock }}
                            </span>
                            @else
                            <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $item->stock }}
                            </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('admin.items.show', $item) }}" class="btn btn-sm btn-outline-info"
                                    title="Detail Barang">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-outline-warning"
                                    title="Edit Barang">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST"
                                    class="js-confirm-delete" data-confirm-title="Hapus Barang?"
                                    data-confirm-text="Barang {{ $item->name }} akan dihapus. Apakah Anda yakin?"
                                    data-confirm-button="Ya, Hapus Barang">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Barang">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox display-4 text-body-secondary"></i>
                            </div>

                            <h6 class="fw-semibold">
                                Belum Ada Data Barang
                            </h6>

                            <p class="text-body-secondary mb-0">
                                @if (request('search'))
                                Tidak ditemukan barang yang sesuai dengan pencarian.
                                @else
                                Silakan tambahkan data barang terlebih dahulu.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($items->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
        @endif

    </div>
</div>

@endsection