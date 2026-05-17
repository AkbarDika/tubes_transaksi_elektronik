@extends('layouts.welcome')

@section('content')

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

{{-- HERO --}}
<section id="hero">
    <div class="hero-overlay"></div>

    <div class="container-sm hero-content">
        <div class="row align-items-center">
            <div class="reveal col-lg-7 col-md-8 text-white">
                <div class="hero-badge">Deant Rental</div>
                <h1 class="hero-title">
                    Solusi Rental Mobil<br>
                    Untuk Perjalanan Tanpa Batas
                </h1>

                <p class="hero-desc">
                    Nikmati perjalanan dengan kendaraan berkualitas, harga transparan, 
                    dan layanan terbaik. Mulai petualangan Anda bersama kami hari ini!
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn-hero btn-primary-hero">
                        Pilih Mobil Sekarang
                    </a>
                    <a href="#section-3" class="btn-hero btn-outline-hero">
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- EXPLORE --}}
<section id="section-2">
    <div class="container-sm reveal">
        <div class="section-header">
            <span class="section-badge">Penawaran Terbaik</span>
            <h2 class="section-title">Jelajahi Armada Kami</h2>
            <p class="section-subtitle">Pilih dari berbagai pilihan mobil berkualitas dengan harga terjangkau</p>
        </div>

        <div class="row mt-5 g-4">
            @forelse($cars as $car)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="car-card">
                        <div class="car-img-wrapper">
                            <img src="{{ asset('storage/' . $car->foto) }}" class="car-img" alt="{{ $car->merk }} {{ $car->model }}">
                            <div class="car-badge">Tersedia</div>
                        </div>

                        <div class="card-body">
                            <h5 class="car-name">{{ $car->merk }} {{ $car->model }}</h5>
                            
                            <div class="price-wrapper">
                                <span class="price-label">Mulai dari</span>
                                <div class="price">
                                    {{ number_format($car->harga_sewa, 0, ',', '.') }}
                                    <span class="price-unit">/ hari</span>
                                </div>
                            </div>

                            <div class="btn-group-car">
                                <a href="{{ route('rental.create', $car->id) }}" class="btn-rent-primary">
                                    Sewa
                                </a>
                                <a href="{{ route('login') }}" class="btn-rent-outline">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Belum ada armada yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('login') }}" class="btn-view-all">
                Lihat Semua Mobil →
            </a>
        </div>
    </div>
</section>



{{-- FITUR UNGGULAN --}}
<section id="features">
    <div class="container-sm reveal">
        <div class="section-header">
            <span class="section-badge">Keunggulan Kami</span>
            <h2 class="section-title">Kenapa Pilih Kami?</h2>
            <p class="section-subtitle">Kami memberikan pelayanan terbaik untuk kenyamanan perjalanan Anda</p>
        </div>

        <div class="row mt-5 g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h5>Armada Berkualitas</h5>
                    <p>Kendaraan bersih, terawat secara rutin, dan siap menemani setiap perjalanan Anda dengan aman dan nyaman.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <h5>Proses Cepat</h5>
                    <p>Pemesanan online yang mudah, konfirmasi instan, dan proses pengambilan kendaraan yang praktis tanpa ribet.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>Asuransi Lengkap</h5>
                    <p>Setiap kendaraan dilengkapi dengan asuransi komprehensif untuk ketenangan pikiran selama perjalanan Anda.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5>Support 24/7</h5>
                    <p>Tim customer service kami siap membantu Anda kapan saja, 24 jam sehari dan 7 hari seminggu.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TESTIMONI / STAT --}}
<section id="stats">
    <div class="container-sm reveal">
        <div class="section-header">
            <span class="section-badge">Statistik</span>
            <h2 class="section-title">Perjalanan yang Telah Kami Layani</h2>
            <p class="section-subtitle">
                Setiap angka adalah cerita kepuasan pelanggan yang kami banggakan
            </p>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="statistik-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h2 class="stat-number">500+</h2>
                    <p class="stat-label">Total Sewa</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="statistik-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"></path>
                        </svg>
                    </div>
                    <h2 class="stat-number">400+</h2>
                    <p class="stat-label">Pelanggan Puas</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="statistik-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <h2 class="stat-number">50+</h2>
                    <p class="stat-label">Armada Mobil</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT --}}
<section id="section-3">
    <div class="container-sm reveal">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-img-wrapper">
                    <img src="{{ asset('storage/rental/deanrent.png') }}" class="img-fluid about-img" alt="Rental Mobil Dean">
                    <div class="about-badge">
                        <div class="badge-content">
                            <span class="badge-number">15+</span>
                            <span class="badge-text">Tahun<br>Pengalaman</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="section-badge">Tentang Kami</span>
                    <h2 class="section-title text-start mb-4">Rental Mobil Dean</h2>
                    <p class="about-text">
                        <strong>RENTAL MOBIL DEAN</strong> adalah penyedia layanan rental mobil terpercaya 
                        sejak tahun 2010. Kami berkomitmen untuk memberikan pengalaman terbaik dengan 
                        armada berkualitas tinggi dan layanan pelanggan yang profesional.
                    </p>
                    <p class="about-text">
                        Dengan pengalaman lebih dari satu dekade, kami telah melayani ribuan pelanggan 
                        dengan kepuasan yang tinggi. Setiap kendaraan dipelihara dengan standar tertinggi 
                        untuk kenyamanan dan keamanan perjalanan Anda.
                    </p>
                    
                    <div class="about-features mt-4">
                        <div class="about-feature-item">
                            <span class="feature-check">✓</span>
                            <span>Armada Terawat & Bersih</span>
                        </div>
                        <div class="about-feature-item">
                            <span class="feature-check">✓</span>
                            <span>Harga Kompetitif</span>
                        </div>
                        <div class="about-feature-item">
                            <span class="feature-check">✓</span>
                            <span>Layanan 24/7</span>
                        </div>
                        <div class="about-feature-item">
                            <span class="feature-check">✓</span>
                            <span>Proses Mudah & Cepat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
#hero {
    position: relative;
    min-height: 90vh;
    background: url('{{ asset('storage/rental/herobg.png') }}') center/cover no-repeat;
    display: flex;
    align-items: center;
    padding: 60px 0;
}

/* ANIMATION BASE */
.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease-out;
}

/* SAAT MUNCUL */
.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

/* OPTIONAL DELAY VARIANTS */
.delay-1 { transition-delay: .1s; }
.delay-2 { transition-delay: .2s; }
.delay-3 { transition-delay: .3s; }
.delay-4 { transition-delay: .4s; }

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const reveals = document.querySelectorAll(".reveal");

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15
    });

    reveals.forEach(el => observer.observe(el));
});
</script>


@endsection
