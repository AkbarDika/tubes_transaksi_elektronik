<!DOCTYPE html>
<html>
<head>
    <title>Admin - Rental Mobil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f5f7fa;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: sticky;
            top: 0;
        }

        .admin-sidebar .sidebar-brand {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Top Bar */
        .admin-topbar {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            sticky: top;
            z-index: 100;
        }

        .admin-topbar .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-topbar .user-info {
            text-align: right;
        }

        .admin-topbar .user-info h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .admin-topbar .user-info p {
            margin: 0;
            font-size: 12px;
            color: #999;
        }

        /* Main Content */
        .admin-content {
            padding: 30px;
        }

        .admin-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .admin-stat-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .admin-stat-box.stat-primary {
            border-left-color: #667eea;
        }

        .admin-stat-box.stat-success {
            border-left-color: #48bb78;
        }

        .admin-stat-box.stat-warning {
            border-left-color: #ed8936;
        }

        .admin-stat-box.stat-danger {
            border-left-color: #f56565;
        }

        .admin-stat-box h3 {
            color: #667eea;
            font-weight: bold;
            margin: 10px 0;
        }

        .admin-stat-box p {
            color: #999;
            font-size: 14px;
            margin: 0;
        }

        .page-title {
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: #667eea;
            font-size: 28px;
        }

        /* Collapse Menu Styling */
        .admin-sidebar .collapse {
            display: none;
        }

        .admin-sidebar .collapse.show {
            display: block;
        }

        .admin-sidebar #mobilMenu .nav-link {
            padding: 8px 20px;
            font-size: 14px;
        }

        /* Pagination Styling */
        .pagination-container {
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
            margin-top: 20px;
        }

        /* Target the flex container inside Laravel's pagination */
        .pagination-container nav div.d-flex,
        .pagination-container nav div.flex.justify-between {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Ensure the 'Showing' text and links are spaced apart even when centered */
        .pagination {
            margin-bottom: 0;
            gap: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                position: relative;
                min-height: auto;
            }

            .admin-content {
                padding: 20px;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2">
            <div class="admin-sidebar">
                <div class="sidebar-brand">
                    <i class="bi bi-shield-lock"></i>
                    ADMIN
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                    
                    <a class="nav-link" href="{{ url('/mobil') }}">
                        <i class="bi bi-car-front"></i>
                        Daftar Mobil
                    </a>
                    <a class="nav-link" href="{{ url('/kategori') }}">
                        <i class="bi bi-car-front"></i>
                        Kategori Mobil  
                    </a>
                    
                    <a class="nav-link" href="{{ url('/pemesanan') }}">
                        <i class="bi bi-person-check"></i>
                        Pemesanan
                    </a>
                    <a class="nav-link" href="{{ url('/pembayaran') }}">
                        <i class="bi bi-credit-card"></i>
                        Pembayaran
                    </a>
                    <a class="nav-link" href="{{ url('/pengembalian') }}">
                        <i class="bi bi-credit-card"></i>
                        Pengembalian
                    </a>
                    <a class="nav-link" href="{{ url('/user') }}">
                        <i class="bi bi-people"></i>
                        Pengguna
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                        <i class="bi bi-file-earmark-pdf"></i>
                        Laporan
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.kontrak.*') ? 'active' : '' }}" href="{{ route('admin.kontrak.index') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        Kontrak
                    </a>
                    <a class="nav-link" href="{{ url('/dashboard') }}">
                        <i class="bi bi-home"></i>
                        Halaman Utama
                    </a>
                </nav>

                <hr style="border-color: rgba(255,255,255,0.2); margin-top: 30px;">

                <div style="padding: 0 20px;">
                    <p style="color: rgba(255,255,255,0.7); font-size: 12px; margin: 0;">{{ auth()->user()->name }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light" style="width: 100%; color: white; border-color: rgba(255,255,255,0.5);">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10">
            <!-- Top Bar -->
            <div class="admin-topbar">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>Selamat datang di Admin Panel</h5>
                    </div>
                    <div class="col-auto">
                        <div class="topbar-user">
                            <div>
                                <i class="bi bi-bell-fill" style="font-size: 18px; color: #667eea;"></i>
                            </div>
                            <div class="user-info">
                                <h6>{{ auth()->user()->name }}</h6>
                                <p>Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
