@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<style>



    .car-preview-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
    }
    
    .car-preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
    }

    .page-section {
        background: linear-gradient(to bottom, #f8f9fa 0%, #ebf5ff 100%);
        position: relative;
        padding-top: 4rem;
        padding-bottom: 6rem;
    }

    .price-tag {
        font-family: 'Outfit', sans-serif;
        background: linear-gradient(45deg, #2b5876, #4e4376);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    /* Input Styling */
    .form-floating > .form-control {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        height: 60px;
    }
    .form-floating > .form-control:focus {
        border-color: #4e4376;
        box-shadow: 0 0 0 4px rgba(78, 67, 118, 0.1);
    }
    .form-floating > label {
        padding-top: 1rem;
    }

    /* Animation Base */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    .delay-1 { transition-delay: 0.1s; }
    .delay-2 { transition-delay: 0.2s; }
    .delay-3 { transition-delay: 0.3s; }

    .total-box {
        /* background-color: #f8f9fa; */
        border: 1px solid lightgray;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .btn-booking {
        background: linear-gradient(135deg, #3498db, #2980b9);
        transition: all 0.3s ease;
    }

    .btn-booking:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(52, 152, 219, 0.3);
        color: white;
    }
    .text-harga {
        background: linear-gradient(135deg, #3498db, #2980b9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }


</style>


{{-- MAIN CONTENT --}}
<section class="page-section">
    <div class="container-sm" style="margin-top: -100px; position: relative; z-index: 10;">
        <div class="row pt-5 mt-5 mb-5">
            <div class="section-header">
                <span class="section-badge">Pemesanan</span>
                <h2 class="section-title">Satu Langkah lagi</h2>
                <p class="section-subtitle">untuk memulai perjalanan seru dengan <strong>{{ $car->merk }} {{ $car->model }}</strong>. Nikmati kenyamanan berkendara terbaik. </p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Left Side - Informasi Mobil -->
            <div class="col-lg-4">
                <div class="car-preview-card reveal delay-1 sticky-top" style="top: 100px;">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $car->foto) }}" alt="{{ $car->merk }} {{ $car->model }}" 
                             class="w-100" style="height: 300px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success bg-gradient px-3 py-2 rounded-pill shadow-sm">Tersedia</span>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-1">{{ $car->merk }} {{ $car->model }}</h4>
                        <div class="mb-4 text-muted small">
                            <i class="fas fa-star text-warning me-1"></i> 5.0 (Review) | Tersewa 50+ kali
                        </div>
                        
                        <!-- Specs Grid -->
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: #f8f9fa;">
                                    <i class="fas fa-calendar-alt text-primary mb-2"></i>
                                    <div class="fw-bold small">{{ $car->tahun }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 rounded-3" style="background: #f8f9fa;">
                                    <i class="fas fa-user-friends text-primary mb-2"></i>
                                    <div class="fw-bold small">{{ $car->jumlah_kursi }} Kursi</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="text-muted fw-medium">Harga / Hari</span>
                            <div class="text-end">
                                <h3 class="fw-bold mb-0 price-tag">
                                    Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Back Button Mobile Only (Hidden Desktop) -->
                <div class="d-block d-lg-none mt-4 text-center">
                    <a href="{{ route('catalog.show', $car->id) }}" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Detail
                    </a>
                </div>
            </div>

            <!-- Right Side - Form Pemesanan -->
            <div class="col-lg-8">

                <div class="form-card p-4 p-md-5 reveal delay-2">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                            <i class="fas fa-file-signature fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Formulir Pemesanan</h4>
                            <p class="text-muted mb-0 small">Silakan lengkapi data durasi sewa Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('rental.store') }}" method="POST" class="needs-validation" novalidate id="rentalForm">
                        @csrf
                        <input type="hidden" name="mobil_id" value="{{ $car->id }}">

                        <div class="row g-4 mb-4">
                            <!-- Tanggal Mulai -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" 
                                           id="tanggal_mulai" name="tanggal_mulai" required 
                                           value="{{ old('tanggal_mulai') }}" placeholder="Mulai">
                                    <label for="tanggal_mulai">Tanggal Mulai Sewa</label>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" 
                                           id="tanggal_selesai" name="tanggal_selesai" required 
                                           value="{{ old('tanggal_selesai') }}" placeholder="Selesai">
                                    <label for="tanggal_selesai">Tanggal Selesai Sewa</label>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Live Calculation Box -->
                        <div class="total-box p-4 rounded-4 position-relative overflow-hidden shadow-sm">
                            <!-- Decor -->
                            <div class="position-absolute top-0 end-0 p-4 opacity-10">
                                <i class="fas fa-wallet fa-6x"></i>
                            </div>
                            
                            <div class="row align-items-center position-relative z-index-1">
                                <div class="col-md-7 border-end border-secondary border-opacity-25">
                                    <span class="text-black text-bold text-uppercase letter-spacing-2">Estimasi Total Biaya</span>
                                    <h2 class="display-5 fw-bold mb-0 text-harga"  id="totalEstimate">Rp 0</h2>
                                    <p class="mb-0 mt-2 text-black-50 " id="durationInfo">
                                        <i class="fas fa-info-circle me-1"></i> Pilih tanggal untuk estimasi harga
                                    </p>
                                </div>
                                <div class="col-md-5 text-md-center mt-3 mt-md-0">
                                    <button type="submit" class="btn btn-booking btn-lg rounded-pill px-4 fw-bold text-white shadow-warning hover-scale w-100">
                                        <i class="fas fa-check-circle me-2"></i> Booking Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Info Alert -->
                    <div class="bg-light p-4 rounded-4 mt-4 border border-dahed border-secondary border-opacity-25">
                        <div class="d-flex">
                            <i class="fas fa-shield-alt text-primary fs-4 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Informasi Penting</h6>
                                <ul class="mb-0 ps-3 text-muted small">
                                    <li class="mb-1">Pastikan tanggal kembali minimal 1 hari setelah tanggal mulai.</li>
                                    <li class="mb-1">Pembayaran dilakukan via transfer setelah konfirmasi.</li>
                                    <li>Membatalkan pesanan H-1 akan dikenakan biaya administrasi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Reveal Animation Setup
    const reveals = document.querySelectorAll(".reveal");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    reveals.forEach(el => observer.observe(el));

    // Pricing Logic
    const hargaSewa = {{ $car->harga_sewa }};
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
    const totalEstimate = document.getElementById('totalEstimate');
    const durationInfo = document.getElementById('durationInfo');

    function hitungTotal() {
        const tanggalMulai = tanggalMulaiInput.value ? new Date(tanggalMulaiInput.value) : null;
        const tanggalSelesai = tanggalSelesaiInput.value ? new Date(tanggalSelesaiInput.value) : null;

        if (!tanggalMulai || !tanggalSelesai) {
            totalEstimate.textContent = 'Rp 0';
            durationInfo.innerHTML = '<i class="fas fa-info-circle me-1"></i> Pilih tanggal untuk estimasi harga';
            durationInfo.className = 'mb-0 mt-2 small';
            return;
        }

        const timeDiff = tanggalSelesai.getTime() - tanggalMulai.getTime();
        const selisihHari = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

        if (selisihHari <= 0) {
            totalEstimate.textContent = 'Rp 0';
            durationInfo.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Durasi minimal 1 hari';
            durationInfo.className = 'mb-0 mt-2 small text-danger fw-bold';
            return;
        }

        durationInfo.className = 'mb-0 mt-2 small'; // Reset styling

        const total = selisihHari * hargaSewa;
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        totalEstimate.textContent = formatter.format(total);
        const hargaFormatted = new Intl.NumberFormat('id-ID').format(hargaSewa);
        durationInfo.innerHTML = `<i class="fas fa-clock me-1"></i> ${selisihHari} Hari x Rp ${hargaFormatted}`;
    }

    tanggalMulaiInput.addEventListener('change', hitungTotal);
    tanggalSelesaiInput.addEventListener('change', hitungTotal);

    // Min Date Logic
    const today = new Date().toISOString().split('T')[0];
    tanggalMulaiInput.setAttribute('min', today);
    
    tanggalMulaiInput.addEventListener('change', function() {
        if (this.value) {
            const minDate = new Date(this.value);
            minDate.setDate(minDate.getDate() + 1);
            tanggalSelesaiInput.setAttribute('min', minDate.toISOString().split('T')[0]);
            
            // Auto update finish date if it's before new min date
            if (tanggalSelesaiInput.value && new Date(tanggalSelesaiInput.value) < minDate) {
                tanggalSelesaiInput.value = minDate.toISOString().split('T')[0];
                hitungTotal();
            }
        }
    });
});
</script>
@endsection
