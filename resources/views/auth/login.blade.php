<x-guest-layout>

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Selamat Datang!</h3>
        <p class="text-body-secondary mb-0">
            Silakan masuk untuk melanjutkan ke sistem inventaris.
        </p>
    </div>

    {{-- Status Session --}}
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">
                Alamat Email
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan alamat email"
                autocomplete="username" required autofocus>

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
                class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                autocomplete="current-password" required>

            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input">

                <label for="remember_me" class="form-check-label">
                    Ingat saya
                </label>
            </div>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-decoration-none small">
                Lupa password?
            </a>
            @endif
        </div>

        {{-- Tombol Login --}}
        <button type="submit" class="btn btn-primary w-100 auth-submit">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Masuk
        </button>

        {{-- Link Register --}}
        <div class="text-center mt-4">
            <span class="text-body-secondary">
                Belum punya akun?
            </span>

            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                Daftar sekarang
            </a>
        </div>

    </form>

</x-guest-layout>