<x-guest-layout>

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Buat Akun Baru</h3>
        <p class="text-body-secondary mb-0">
            Daftarkan akun untuk menggunakan sistem inventaris sekolah.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama Lengkap --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">
                Nama Lengkap
            </label>

            <input id="name" type="text" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap"
                autocomplete="name" required autofocus>

            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">
                Alamat Email
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email"
                autocomplete="username" required>

            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">
                Password
            </label>

            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter"
                autocomplete="new-password" required>

            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small class="text-body-secondary">
                Gunakan minimal 8 karakter untuk password.
            </small>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                Konfirmasi Password
            </label>

            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control"
                placeholder="Masukkan ulang password" autocomplete="new-password" required>
        </div>

        {{-- Tombol Register --}}
        <button type="submit" class="btn btn-primary w-100 auth-submit">
            <i class="bi bi-person-plus me-2"></i>
            Daftar Sekarang
        </button>

        {{-- Link Login --}}
        <div class="text-center mt-4">
            <span class="text-body-secondary">
                Sudah memiliki akun?
            </span>

            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                Masuk sekarang
            </a>
        </div>

    </form>

</x-guest-layout>