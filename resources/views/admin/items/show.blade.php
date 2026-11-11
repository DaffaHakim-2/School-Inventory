@extends('layouts.admin')

@section('title', 'Detail Barang')

@section('page-heading')
<div>
    <h1 class="h3 mb-1">Detail Barang</h1>
    <p class="text-body-secondary mb-0">
        Informasi barang dan riwayat pergerakan stok inventaris.
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

{{-- Informasi Barang --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">

            <div>
                <h5 class="mb-1">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Informasi Barang
                </h5>
                <small class="text-body-secondary">
                    Detail informasi barang inventaris sekolah.
                </small>
            </div>

            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i>
                Edit Barang
            </a>

        </div>
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Kode Barang --}}
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="text-body-secondary small mb-2">
                        <i class="bi bi-upc-scan me-1"></i>
                        Kode Barang
                    </div>

                    <div class="fw-semibold fs-5">
                        {{ $item->code }}
                    </div>
                </div>
            </div>

            {{-- Nama Barang --}}
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="text-body-secondary small mb-2">
                        <i class="bi bi-box me-1"></i>
                        Nama Barang
                    </div>

                    <div class="fw-semibold fs-5">
                        {{ $item->name }}
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="text-body-secondary small mb-2">
                        <i class="bi bi-tags me-1"></i>
                        Kategori
                    </div>

                    <div class="fw-semibold">
                        {{ $item->category->name ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Stok --}}
            <div class="col-12 col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="text-body-secondary small mb-2">
                        <i class="bi bi-clipboard-data me-1"></i>
                        Stok Tersedia
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-2 fw-bold
                                {{ $item->stock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $item->stock }}
                        </span>

                        <span class="text-body-secondary">
                            unit
                        </span>
                    </div>

                    @if ($item->stock > 0)
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Stok tersedia
                    </small>
                    @else
                    <small class="text-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Stok habis
                    </small>
                    @endif
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="col-12">
                <div class="border rounded-3 p-3">
                    <div class="text-body-secondary small mb-2">
                        <i class="bi bi-card-text me-1"></i>
                        Deskripsi
                    </div>

                    <div>
                        {{ $item->description ?: 'Tidak ada deskripsi.' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Riwayat Pergerakan Stok --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <div>
            <h5 class="mb-1">
                <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                Riwayat Pergerakan Stok
            </h5>

            <small class="text-body-secondary">
                Catatan seluruh perubahan stok barang.
            </small>
        </div>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Jenis Pergerakan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th>Dicatat Oleh</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($item->itemMovements as $movement)
                    <tr>

                        <td class="ps-3">
                            <div class="fw-medium">
                                {{ $movement->created_at?->format('d-m-Y') ?? '-' }}
                            </div>

                            <small class="text-body-secondary">
                                {{ $movement->created_at?->format('H:i') ?? '' }}
                            </small>
                        </td>

                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                            </span>
                        </td>

                        <td class="text-center fw-semibold">
                            {{ $movement->quantity }}
                        </td>

                        <td class="text-center">
                            {{ $movement->stock_before }}
                        </td>

                        <td class="text-center">
                            <span class="fw-semibold">
                                {{ $movement->stock_after }}
                            </span>
                        </td>

                        <td>
                            {{ $movement->user->name ?? 'Sistem' }}
                        </td>

                        <td>
                            <span class="text-body-secondary">
                                {{ $movement->description ?? '-' }}
                            </span>
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-clock-history display-5 text-body-secondary"></i>

                            <h6 class="fw-semibold mt-3">
                                Belum Ada Riwayat Stok
                            </h6>

                            <p class="text-body-secondary mb-0">
                                Belum terdapat catatan pergerakan stok untuk barang ini.
                            </p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Penyesuaian Stok --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-body py-3">
        <div>
            <h5 class="mb-1">
                <i class="bi bi-sliders me-2 text-primary"></i>
                Penyesuaian Stok
            </h5>

            <small class="text-body-secondary">
                Catat penambahan atau pengurangan stok barang.
            </small>
        </div>
    </div>

    <div class="card-body">

        {{-- Informasi Stok Saat Ini --}}
        <div class="alert alert-info d-flex align-items-center gap-3">
            <i class="bi bi-info-circle-fill fs-4"></i>

            <div>
                <div class="small">Stok tersedia saat ini</div>
                <strong class="fs-5">
                    {{ $item->stock }} unit
                </strong>
            </div>
        </div>

        {{-- Ringkasan Error Validasi --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Terdapat kesalahan pada input:
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.items.adjust-stock', $item) }}" method="POST">

            @csrf

            <div class="row g-3">

                {{-- Jenis Pergerakan --}}
                <div class="col-12 col-md-6">
                    <label for="movement_type" class="form-label fw-semibold">
                        Jenis Pergerakan
                        <span class="text-danger">*</span>
                    </label>

                    <select name="movement_type" id="movement_type"
                        class="form-select @error('movement_type') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis --</option>

                        <option value="stock_in" {{ old('movement_type') == 'stock_in' ? 'selected' : '' }}>
                            Stok Masuk
                        </option>

                        <option value="stock_adjustment"
                            {{ old('movement_type') == 'stock_adjustment' ? 'selected' : '' }}>
                            Penyesuaian Stok
                        </option>
                    </select>

                    @error('movement_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Arah Perubahan --}}
                <div class="col-12 col-md-6">
                    <label for="direction" class="form-label fw-semibold">
                        Arah Perubahan
                        <span class="text-danger">*</span>
                    </label>

                    <select name="direction" id="direction" class="form-select @error('direction') is-invalid @enderror"
                        required>
                        <option value="">-- Pilih Arah --</option>

                        <option value="increase" {{ old('direction') == 'increase' ? 'selected' : '' }}>
                            Menambah Stok
                        </option>

                        <option value="decrease" {{ old('direction') == 'decrease' ? 'selected' : '' }}>
                            Mengurangi Stok
                        </option>
                    </select>

                    @error('direction')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jumlah Perubahan --}}
                <div class="col-12 col-md-6">
                    <label for="quantity" class="form-label fw-semibold">
                        Jumlah Perubahan
                        <span class="text-danger">*</span>
                    </label>

                    <input type="number" name="quantity" id="quantity"
                        class="form-control @error('quantity') is-invalid @enderror" min="1" max="2147483647"
                        value="{{ old('quantity') }}" placeholder="Masukkan jumlah perubahan" required>

                    @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alasan Penyesuaian --}}
                <div class="col-12">
                    <label for="description" class="form-label fw-semibold">
                        Alasan Penyesuaian
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror" rows="3" maxlength="1000"
                        placeholder="Jelaskan alasan penambahan atau pengurangan stok..."
                        required>{{ old('description') }}</textarea>

                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Simpan --}}
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Penyesuaian Stok
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

{{-- Tombol Kembali --}}
<div class="mb-4">
    <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali ke Daftar Barang
    </a>
</div>

@endsection