@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<section id="catalog-content" style="padding: 60px 0; min-height: 100vh;">
    <div class="container-sm">
        <!-- Breadcrumb & Header -->
        <div class="reveal mb-5">
            <div class="section-header text-start">
                <span class="section-badge">{{ $car->kategori_mobil_id === 1 ? 'MPV' : ($car->kategori_mobil_id === 2 ? 'SUV' : 'Sedan') }}</span>
                <h2 class="section-title mb-0">{{ $car->merk }} {{ $car->model }}</h2>
                <p class="text-muted mt-2"><i class="bi bi-calendar3 me-2"></i>Tahun: {{ $car->tahun ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Gambar Mobil -->
            <div class="col-lg-7 reveal delay-1">
                <div class="car-card overflow-hidden" style="height: auto; cursor: default;">
                    <div class="car-img-wrapper" style="height: 450px;">
                        <img 
                            src="{{ asset('storage/' . $car->foto) }}" 
                            class="car-img w-100 h-100" 
                            style="object-fit: cover;"
                            alt="{{ $car->merk }} {{ $car->model }}"
                        >
                        <div class="car-badge">Tersedia</div>
                    </div>
                </div>

                <!-- Deskripsi (Desktop View) -->
                <div class="mt-5 d-none d-lg-block">
                    <h4 class="fw-bold mb-4">Deskripsi Kendaraan</h4>
                    <div class="card border-0 shadow-sm p-4 rounded-4" style="background: rgba(255,255,255,0.6); backdrop-filter: blur(10px);">
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            {{ $car->merk }} {{ $car->model }} adalah pilihan sempurna untuk kebutuhan transportasi Anda. 
                            Dengan desain modern, fitur lengkap, dan kenyamanan maksimal, mobil ini cocok untuk berbagai 
                            keperluan mulai dari liburan keluarga hingga perjalanan bisnis. Kami menjamin kendaraan dalam 
                            kondisi prima dengan perawatan rutin dan asuransi lengkap.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail & Aksi -->
            <div class="col-lg-5 reveal delay-2">
                <!-- Info Utama -->
                <div class="car-card p-4 mb-4">
                    <div class="price-wrapper mb-4">
                        <span class="price-label">Harga Sewa</span>
                        <div class="price">
                            Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}
                            <span class="price-unit">/ hari</span>
                        </div>
                        <small class="text-muted mt-2 d-block">* Harga nett, termasuk asuransi standar</small>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="{{ route('rental.create', $car->id) }}" class="btn-rent-primary py-3 fs-6">
                            <i class="bi bi-calendar-check me-2"></i>Sewa Sekarang
                        </a>
                    </div>
                </div>

                <!-- Spesifikasi -->
                <div class="car-card p-4 mb-4">
                    <h5 class="fw-bold mb-4">Spesifikasi</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">Kursi</small>
                                <span class="fw-bold"><i class="bi bi-people me-2"></i>{{ $car->jumlah_kursi ?? 5 }} Kursi</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">Bahan Bakar</small>
                                <span class="fw-bold"><i class="bi bi-fuel-pump me-2"></i>{{ $car->jenis_bahan_bakar ?? 'Bensin' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">Transmisi</small>
                                <span class="fw-bold"><i class="bi bi-gear-wide-connected me-2"></i>Otomatis</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light">
                                <small class="text-muted d-block mb-1">Tipe</small>
                                <span class="fw-bold"><i class="bi bi-truck me-2"></i>SUV</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fitur -->
                <div class="car-card p-4">
                    <h5 class="fw-bold mb-4">Fitur & Keamanan</h5>
                    <div class="row g-2">
                        @php
                            $features = ['AC', 'Power Steering', 'Airbag', 'ABS', 'Bluetooth', 'Camera Mundur'];
                        @endphp
                        @foreach($features as $feature)
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-check2-circle text-primary"></i>
                                <span class="text-muted" style="font-size: 14px;">{{ $feature }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi (Mobile View) -->
        <div class="row mt-4 d-lg-none reveal">
            <div class="col-12">
                <h4 class="fw-bold mb-4">Deskripsi Kendaraan</h4>
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                        {{ $car->merk }} {{ $car->model }} adalah pilihan sempurna untuk kebutuhan transportasi Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedCars->count() > 0)
        <div class="mt-5 pt-5">
            <div class="reveal mb-4">
                <div class="section-header text-start">
                    <span class="section-badge">Rekomendasi</span>
                    <h2 class="section-title">Mobil Serupa Lainnya</h2>
                </div>
            </div>
            
            <div class="row mt-4 g-4">
                @foreach($relatedCars as $relatedCar)
                    <div class="col-lg-3 col-md-6 reveal delay-{{ $loop->iteration }}">
                        <div class="car-card">
                            <div class="car-img-wrapper">
                                <img 
                                    src="{{ asset('storage/' . $relatedCar->foto) }}" 
                                    class="car-img" 
                                    alt="{{ $relatedCar->merk }} {{ $relatedCar->model }}"
                                >
                                <div class="car-badge">Tersedia</div>
                            </div>

                            <div class="card-body">
                                <h5 class="car-name">
                                    {{ $relatedCar->merk }} {{ $relatedCar->model }}
                                </h5>
                                
                                <div class="price-wrapper">
                                    <span class="price-label">Mulai dari</span>
                                    <div class="price">
                                        Rp {{ number_format($relatedCar->harga_sewa,0,',','.') }}
                                        <span class="price-unit">/ hari</span>
                                    </div>
                                </div>

                                <div class="btn-group-car">
                                    <a href="{{ route('rental.create', $relatedCar->id) }}" class="btn-rent-primary">
                                        Sewa
                                    </a>
                                    <a href="{{ route('catalog.show', $relatedCar->id) }}" class="btn-rent-outline">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .car-card {
        height: auto !important;
    }

    #catalog-content {
        background: linear-gradient(to bottom, #ffffff 0%, #ebf5ff 100%);
        background-attachment: fixed;
    }

    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.15, 0.45, 0.35, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Delay animations */
    .delay-1 { transition-delay: 0.1s; }
    .delay-2 { transition-delay: 0.2s; }
    .delay-3 { transition-delay: 0.3s; }
    .delay-4 { transition-delay: 0.4s; }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "•";
        color: #6c757d;
    }
    
    .bg-light {
        background-color: #f8fafc !important;
    }

    .row.g-5 {
        --bs-gutter-x: 3rem;
    }
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
        threshold: 0.1
    });

    reveals.forEach(el => observer.observe(el));
});
</script>

@endsection
