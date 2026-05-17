<!DOCTYPE html>
<html>
<head>
    <title>Petugas - Rental Mobil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f5f7fa;
        }

        /* Sidebar Styling */
        .petugas-sidebar {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: sticky;
            top: 0;
        }

        .petugas-sidebar .sidebar-brand {
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

        .petugas-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .petugas-sidebar .nav-link:hover,
        .petugas-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .petugas-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Top Bar */
        .petugas-topbar {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            sticky: top;
            z-index: 100;
        }

        .petugas-topbar .topbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .petugas-topbar .user-info {
            text-align: right;
        }

        .petugas-topbar .user-info h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .petugas-topbar .user-info p {
            margin: 0;
            font-size: 12px;
            color: #999;
        }

        /* Main Content */
        .petugas-content {
            padding: 30px;
        }

        .petugas-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .petugas-stat-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .petugas-stat-box.stat-primary {
            border-left-color: #48bb78;
        }

        .petugas-stat-box.stat-success {
            border-left-color: #38a169;
        }

        .petugas-stat-box.stat-warning {
            border-left-color: #ed8936;
        }

        .petugas-stat-box.stat-danger {
            border-left-color: #f56565;
        }

        .petugas-stat-box h3 {
            color: #48bb78;
            font-weight: bold;
            margin: 10px 0;
        }

        .petugas-stat-box p {
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
            color: #48bb78;
            font-size: 28px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .petugas-sidebar {
                position: relative;
                min-height: auto;
            }

            .petugas-content {
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
            <div class="petugas-sidebar">
                <div class="sidebar-brand">
                    <i class="bi bi-person-badge"></i>
                    PETUGAS
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->is('petugas') ? 'active' : '' }}" href="{{ url('/petugas') }}">
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                    
                    <a class="nav-link {{ request()->is('petugas/pemesanan') ? 'active' : '' }}" href="{{ url('/petugas/pemesanan') }}">
                        <i class="bi bi-calendar-check"></i>
                        Pemesanan
                    </a>
                    
                    <a class="nav-link {{ request()->is('petugas/pengembalian') ? 'active' : '' }}" href="{{ url('/petugas/pengembalian') }}">
                        <i class="bi bi-arrow-return-left"></i>
                        Pengembalian
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
            <div class="petugas-topbar">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>Selamat datang di Panel Petugas</h5>
                    </div>
                    <div class="col-auto">
                        <div class="topbar-user">
                            <div>
                                <i class="bi bi-bell-fill" style="font-size: 18px; color: #48bb78;"></i>
                            </div>
                            <div class="user-info">
                                <h6>{{ auth()->user()->name }}</h6>
                                <p>Petugas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="petugas-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
