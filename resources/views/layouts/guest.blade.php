<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventaris Sekolah') }}</title>

    {{-- Bootstrap Icons, Bootstrap, dan AdminLTE --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    {{-- Mengikuti tema Dashboard --}}
    <script>
    (function() {
        const savedTheme = localStorage.getItem('inventaris-theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    })();
    </script>

    <style>
    body {
        min-height: 100vh;
        background-color: var(--bs-tertiary-bg);
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }

    .auth-container {
        width: 100%;
        max-width: 1050px;
    }

    .auth-card {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .auth-brand-panel {
        background: linear-gradient(145deg, #0d6efd, #084298);
        color: #fff;
        height: 100%;
        min-height: 540px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px;
    }

    /* .auth-form-panel {
        height: 100%;
        min-height: 540px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px;
    } */

    .auth-brand-icon {
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.16);
        font-size: 38px;
        margin-bottom: 28px;
    }

    .auth-form-panel {
        padding: 48px;
    }

    .auth-form-panel .form-control {
        min-height: 46px;
    }

    .auth-submit {
        min-height: 46px;
        font-weight: 600;
    }

    .auth-footer {
        color: var(--bs-secondary-color);
        font-size: 13px;
    }

    @media (max-width: 767.98px) {
        .auth-wrapper {
            padding: 20px 12px;
        }

        .auth-brand-panel {
            min-height: auto;
            padding: 30px 24px;
        }

        .auth-brand-icon {
            width: 58px;
            height: 58px;
            font-size: 28px;
            margin-bottom: 18px;
        }

        .auth-form-panel {
            padding: 30px 24px;
        }
    }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="card auth-card">
                <div class="row g-0">

                    {{-- Panel Identitas Aplikasi --}}
                    <div class="col-md-6">
                        <div class="auth-brand-panel">

                            <div class="auth-brand-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <h2 class="fw-bold mb-3">
                                Inventaris Sekolah
                            </h2>

                            <p class="mb-4 opacity-75">
                                Sistem Informasi Inventaris Sekolah untuk
                                membantu pengelolaan barang, peminjaman,
                                dan pengembalian secara terstruktur.
                            </p>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span>Pengelolaan data barang</span>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span>Pencatatan peminjaman</span>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span>Monitoring pengembalian</span>
                            </div>

                        </div>
                    </div>

                    {{-- Panel Form Login / Register --}}
                    <div class="col-md-6">
                        <div class="auth-form-panel">

                            {{ $slot }}

                            <div class="auth-footer text-center mt-4">
                                &copy; {{ date('Y') }} Sistem Informasi Inventaris Sekolah
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>