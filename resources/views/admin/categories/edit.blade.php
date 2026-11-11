@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-heading', 'Edit Kategori')

@section('content')

<!-- Informasi Halaman -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-2">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    Edit Kategori
                </h4>

                <p class="text-body-secondary mb-0">
                    Perbarui informasi kategori barang inventaris sekolah.
                </p>

            </div>
        </div>
    </div>
</div>

<!-- Form Edit Kategori -->
<div class="row">
    <div class="col-12 col-lg-8 col-xl-7">

        <div class="card shadow-sm">

            <div class="card-header py-3">
                <h5 class="card-title fw-bold mb-0">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Form Edit Kategori
                </h5>
            </div>

            <div class="card-body p-4">

                <!-- Informasi Kategori -->
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-info-circle-fill fs-5 me-3"></i>

                    <div>
                        <div class="fw-semibold">
                            Kategori yang sedang diedit
                        </div>

                        <div>
                            {{ $category->name }}
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Error Validasi -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Terdapat kesalahan pada input:
                    </div>

                    <ul class="mb-0 ps-4">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.categories.update', $category) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <!-- Nama Kategori -->
                    <div class="mb-4">

                        <label for="name" class="form-label fw-semibold">
                            Nama Kategori
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-tag"></i>
                            </span>

                            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Elektronik" required autofocus>

                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        <div class="form-text">
                            Ubah nama kategori sesuai kebutuhan.
                        </div>

                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">

                        <label for="description" class="form-label fw-semibold">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="form-text">
                            Perbarui penjelasan mengenai kategori ini.
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-wrap gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection