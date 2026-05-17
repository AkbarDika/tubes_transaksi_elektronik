@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">




<section id="catalog-content" style="padding: 60px 0;">
    <div class="container-sm reveal">
        <div class="section-header">
            <span class="section-badge">Penawaran Terbaik</span>
            <h2 class="section-title">Jelajahi Armada Kami</h2>
            <p class="section-subtitle">Pilih dari berbagai pilihan mobil berkualitas dengan harga terjangkau</p>
        </div>
        <div class="row">

            <!-- Daftar Mobil -->
            <div class="col-lg-12">
                <!-- Header dengan sorting -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-0"><strong>{{ $cars->total() ?? 0 }} Mobil Tersedia</strong></h5>
                        @if(request('q'))
                            <small class="text-muted">Hasil pencarian untuk: <strong>{{ request('q') }}</strong></small>
                        @endif
                    </div>
                    <div>
                        <select id="sort-select" class="form-select form-select-sm" style="width: auto;">
                            <option value="">Urutkan...</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                    </div>
                </div>

                <!-- Grid Mobil -->
                <div class="row mt-5 g-4">
                    @foreach ($cars as $car)
                        <div class="col-xl-3 col-lg-4 col-md-6 reveal delay-{{ $loop->iteration }}">
                            <div class="car-card">
                                <div class="car-img-wrapper">
                                    <img 
                                        src="{{ asset('storage/' . $car->foto) }}" 
                                        class="car-img" 
                                        alt="{{ $car->merk }} {{ $car->model }}"
                                    >
                                    <div class="car-badge">Tersedia</div>
                                </div>

                                <div class="card-body">
                                    <h5 class="car-name">
                                        {{ $car->merk }} {{ $car->model }}
                                    </h5>
                                    
                                    <div class="price-wrapper">
                                        <span class="price-label">Mulai dari</span>
                                        <div class="price">
                                            Rp {{ number_format($car->harga_sewa,0,',','.') }}
                                            <span class="price-unit">/ hari</span>
                                        </div>
                                    </div>

                                    <div class="btn-group-car">
                                        <a href="{{ route('rental.create', $car->id) }}" class="btn-rent-primary">
                                            Sewa 
                                        </a>
                                        <a href="{{ route('catalog.show', $car->id) }}" class="btn-rent-outline">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <!-- Pagination -->
                @if($cars->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($cars->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">← Sebelumnya</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $cars->previousPageUrl() }}">← Sebelumnya</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($cars->getUrlRange(1, $cars->lastPage()) as $page => $url)
                                    @if ($page == $cars->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($cars->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $cars->nextPageUrl() }}">Selanjutnya →</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Selanjutnya →</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    #catalog-content{
        background: linear-gradient(to bottom,  #ffffff 0%, #ebf5ff 100%,#ffffff 100%);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .form-range {
        height: 5px;
    }

    .form-range::-webkit-slider-thumb {
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #667eea;
        cursor: pointer;
        border: none;
    }

    .form-range::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #667eea;
        cursor: pointer;
        border: none;
    }

    .list-group-item-action {
        color: #667eea;
        padding: 8px 12px;
        font-size: 14px;
    }

    .list-group-item-action:hover {
        background-color: #f0f0f0;
        color: #764ba2;
    }
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
</style>

<script>
    const minInput = document.getElementById('min-price');
    const maxInput = document.getElementById('max-price');
    const minVal = document.getElementById('min-val');
    const maxVal = document.getElementById('max-val');

    if (minInput) {
        minInput.addEventListener('input', function() {
            minVal.textContent = new Intl.NumberFormat('id-ID').format(this.value);
        });
    }

    if (maxInput) {
        maxInput.addEventListener('input', function() {
            maxVal.textContent = new Intl.NumberFormat('id-ID').format(this.value);
        });
    }

    // Logic Sorting JavaScript
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const url = new URL(window.location.href);
            
            if (sortValue) {
                url.searchParams.set('sort', sortValue);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Tetap pertahankan parameter pencarian lain jika ada
            window.location.href = url.toString();
        });
    }
</script>
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