<!DOCTYPE html>
<html>
<head>
    <title>Rental Mobil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=receipt_long" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            min-height: 200vh; /* hanya untuk demo scroll */
            /* background: linear-gradient(180deg, #e0f2fe, #f8fafc); */
        }

        /* Glass Navbar */
        .navbar-glass {
            background: rgba(173, 216, 230, 0.35); /* biru muda transparan */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .navbar-glass .navbar-brand,
        .navbar-glass .nav-link,
        .navbar-glass span {
            color: #0f172a !important;
            font-weight: 500;
        }

        .navbar-glass .btn-danger {
            border-radius: 20px;
            padding: 5px 14px;
        }

        /* Main Content Wrapper */
        main {
            min-height: calc(100vh - 200px);
            padding-top: 80px;
        }

        .footer {
            background-color: #0a0e14; /* Warna gelap dasar */
            background: linear-gradient(to right, #0a0e14 70%, #1a3a5a 100%); /* Gradasi biru di kanan */
            color: #ffffff;
            padding: 50px 8% 20px 8%;
            font-size: 14px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }

        /* Logo & Brand */
        .brand-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .brand-logo {
            width: 40px;
            height: 40px;
            background-color: #3498db; /* Biru Muda */
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
        }
        .brand-text h2 { margin: 0; font-size: 18px; color: #fff; }
        .brand-text p { margin: 0; font-size: 12px; color: #bbb; }

        /* Titles */
        .footer-section h3 {
            color: #3498db; /* Biru Muda */
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Contact Info */
        .contact-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .contact-item i {
            color: #3498db;
            margin-right: 15px;
            margin-top: 3px;
        }
        .contact-text b { display: block; margin-bottom: 2px; }
        .contact-text span { color: #bbb; font-size: 12px; }

        /* List Program */
        .footer-list {
            list-style: none;
            padding: 0;
        }
        .footer-list li {
            margin-bottom: 10px;
            color: #ddd;
            position: relative;
            padding-left: 15px;
        }
        .footer-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #fff;
        }

        /* Social Media */
        .social-icons {
            display: flex;
            gap: 15px;
        }
        .social-box {
            width: 35px;
            height: 35px;
            background-color: #1e252e;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }
        .social-box:hover { background-color: #3498db; }

        /* Copyright Area */
        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 20px;
            color: #888;
            font-size: 13px;
        }
        .navbar-glass .nav-link {
            position: relative;
            margin-right: 10px;
        }

        .navbar-glass .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #3498db;
            transition: .3s;
        }

        .navbar-glass .nav-link:hover::after,
        .navbar-glass .nav-link.active::after {
            width: 100%;
        }
        .dropdown-menu {
            animation: fadeDown .2s ease;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .dropdown-menu {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        .btn-outline-primary {
            border-color: rgba(0, 0, 0, 0.6);
            color: black;
        }

        .btn-outline-primary:hover {
            background: rgba(107, 60, 60, 0.15);
            border-color: black;
            color: black;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f8cff, #6f6cff);
            border: none;
        }




    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass fixed-top">
    <div class="container-sm">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            Dean RentCar
        </a>

        <!-- Toggle Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- MENU KIRI -->
            <ul class="navbar-nav ms-3 me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="#hero">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}"
                       href="{{ route('login') }}">
                        Katalog
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#section-3">
                        Tentang Kami
                    </a>
                </li>
            </ul>

            <!-- MENU KANAN -->
            <div class="d-flex align-items-center gap-2">

                @guest
                    <!-- LOGIN -->
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">
                        Login
                    </a>

                    <!-- REGISTER -->
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">
                        Sign Up
                    </a>
                @endguest

                @auth
                    <!-- JIKA SUDAH LOGIN (opsional) -->
                    <span class="fw-semibold">
                        {{ auth()->user()->name }}
                    </span>
                @endauth

            </div>


        </div>
    </div>
</nav>


<!-- Spacer supaya konten tidak ketutup navbar -->
{{-- <div style="height: 90px;"></div> --}}

<main style="margin:0 0 0 0 !important;padding:3.5% 0 0 0 !important;">
    @yield('content')
</main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <div class="brand-header">
                    <div class="brand-logo"><i class="fas fa-car"></i></div>
                    <div class="brand-text">
                        <h2>Dean RentCar</h2>
                        <p>Solusi Perjalanan Anda</p>
                    </div>
                </div>
                
                <h3>Kontak & Alamat</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="contact-text">
                        <b>Kampus ITENAS Bandung</b>
                        Jl. PHH. Mustofa No.23, Neglasari, Kec.<br>
                        Cibeunying Kaler, Kota Bandung, Jawa Barat 40124
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <div class="contact-text">
                        <b>+62 812 3456 7890</b>
                        Senin - Sabtu, 08:00 - 20:00
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div class="contact-text">
                        <b>kelompok5@itenas.ac.id</b>
                        Email Resmi Project UAS
                    </div>
                </div>
            </div>

            <div class="footer-section">
                <h3>Layanan Unggulan</h3>
                <ul class="footer-list">
                    <li>Sewa Mobil Lepas Kunci</li>
                    <li>Sewa Mobil + Driver</li>
                    <li>Antar Jemput Bandara</li>
                    <li>Sewa Mobil Pengantin</li>
                    <li>Paket Tour Wisata</li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Ikuti Kami</h3>
                <div class="social-icons">
                    <a href="#" class="social-box"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-box"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-box"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-box"><i class="far fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © 2025 Pengembangan Sistem Informasi Rental Mobil - Kelompok 5.
        </div>
    </footer>

</body>
</html>
