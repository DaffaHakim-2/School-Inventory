<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Pengaturan Tema -->
    <script>
    (() => {
        const savedTheme = localStorage.getItem('inventaris-theme');
        const theme = savedTheme === 'dark' ? 'dark' : 'light';

        document.documentElement.setAttribute('data-bs-theme', theme);
    })();
    </script>
    <title>@yield('title', 'Dashboard Admin') - Inventaris Sekolah</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- AdminLTE CSS dan JavaScript -->
    @vite([
    'resources/css/admin.css',
    'resources/js/admin.js'
    ])

    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        <!-- NAVBAR -->
        <nav class="app-header navbar navbar-expand bg-body">

            <div class="container-fluid">

                <!-- Tombol Sidebar -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>

                <!-- Informasi Pengguna dan Pengaturan Tema -->
                <ul class="navbar-nav ms-auto">

                    <!-- Tombol Pengubah Tema -->
                    <li class="nav-item d-flex align-items-center me-3">
                        <button type="button" id="theme-toggle" class="btn btn-outline-secondary btn-sm"
                            title="Ganti tema">

                            <i id="theme-icon" class="bi bi-moon-stars"></i>

                            <span id="theme-label" class="ms-1 d-none d-md-inline">
                                Dark Mode
                            </span>
                        </button>
                    </li>
                    <li class="nav-item d-flex align-items-center me-3">
                        <span class="text-secondary">
                            {{ auth()->user()->name }}
                        </span>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>
        </nav>

        <!-- SIDEBAR -->
        <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">

            <!-- Brand -->
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link text-decoration-none">

                    <i class="bi bi-building me-2"></i>

                    <span class="brand-text fw-light">
                        Inventaris Sekolah
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu -->
            <div class="sidebar-wrapper">

                <nav class="mt-2">

                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Master Data -->
                        <li class="nav-header">DATA BARANG</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}"
                                class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-tags"></i>
                                <p>Kategori Barang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.items.index') }}"
                                class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>Data Barang</p>
                            </a>
                        </li>

                        <!-- Transaksi -->
                        <li class="nav-header">TRANSAKSI</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.loans.index') }}"
                                class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-clipboard-check"></i>
                                <p>Peminjaman</p>
                            </a>
                        </li>

                        <!-- Pengembalian diproses melalui detail peminjaman -->
                        <li class="nav-item">
                            <a href="{{ route('admin.returns.index') }}"
                                class="nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-arrow-return-left"></i>
                                <p>Pengembalian</p>
                            </a>
                        </li>

                        <!-- Menu activity log -->
                        <li class="nav-item">
                            <a href="{{ route('admin.activity-logs.index') }}"
                                class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-clock-history"></i>
                                <p>Activity Log</p>
                            </a>
                        </li>

                        <!-- Manajemen Akun User -->
                        <li class="nav-header">LAINNYA</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-people"></i>
                                <p>Manajemen Pengguna</p>
                            </a>
                        </li>

                        <!-- Akun -->
                        <li class="nav-header">AKUN</li>

                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>Profil</p>
                            </a>
                        </li>

                    </ul>

                </nav>

            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="app-main">

            <!-- Judul Halaman -->
            <div class="app-content-header">

                <div class="container-fluid">

                    <h3 class="mb-0">
                        @yield('page-heading', 'Dashboard Admin')
                    </h3>

                </div>

            </div>

            <!-- Isi Halaman -->
            <div class="app-content">

                <div class="container-fluid">

                    @yield('content')

                </div>

            </div>

        </main>

        <!-- FOOTER -->
        <footer class="app-footer">

            <strong>
                Sistem Informasi Inventaris Sekolah
            </strong>

        </footer>

    </div>

    @stack('scripts')

</body>

</html>