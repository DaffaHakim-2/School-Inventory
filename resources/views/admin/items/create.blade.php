@extends('layouts.admin')

@section('title', 'Tambah Barang')

@section('page-heading')
<div>
    <h1 class="h3 mb-1">Tambah Barang</h1>
    <p class="text-body-secondary mb-0">
        Tambahkan data barang inventaris sekolah.
    </p>
</div>
@endsection

@section('content')

{{-- Ringkasan Error Validasi --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="fw-semibold mb-2">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Terdapat kesalahan pada input:
    </div>

    <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm">

    <div class="card-header bg-body py-3">
        <h5 class="mb-1">
            <i class="bi bi-box-seam me-2 text-primary"></i>
            Form Tambah Barang
        </h5>

        <small class="text-body-secondary">
            Lengkapi informasi barang dan stok awal.
        </small>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.items.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- Kategori --}}
                <div class="col-12">
                    <label for="category_id" class="form-label fw-semibold">
                        Kategori
                        <span class="text-danger">*</span>
                    </label>

                    <select name="category_id" id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kode Barang --}}
                <div class="col-12 col-md-6">
                    <label for="code" class="form-label fw-semibold">
                        Kode Barang
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: ELK-011"
                        class="form-control @error('code') is-invalid @enderror" required>

                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama Barang --}}
                <div class="col-12 col-md-6">
                    <label for="name" class="form-label fw-semibold">
                        Nama Barang
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        placeholder="Masukkan nama barang" class="form-control @error('name') is-invalid @enderror"
                        required>

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="col-12">
                    <label for="description" class="form-label fw-semibold">
                        Deskripsi
                    </label>

                    <textarea name="description" id="description" rows="4"
                        placeholder="Masukkan deskripsi barang (opsional)"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stok Awal --}}
                <div class="col-12">
                    <label for="initial_stock" class="form-label fw-semibold">
                        Stok Awal
                        <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="initial_stock" id="initial_stock" value="{{ old('initial_stock', 0) }}"
                        min="0" max="2147483647" class="form-control @error('initial_stock') is-invalid @enderror"
                        required>

                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Stok awal akan otomatis dicatat dalam riwayat pergerakan stok.
                    </div>

                    @error('initial_stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <hr class="my-4">

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>
                    Simpan Barang
                </button>

            </div>

        </form>

    </div>
</div>

@endsection