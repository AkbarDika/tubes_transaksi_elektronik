@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
    .modal-backdrop.show {
        opacity: 0.2; /* default 0.5 */
    }
    
    .page-section {
        background: linear-gradient(to bottom, #ffffff 0%, #ebf5ff 100%);
        min-height: 80vh; /* Reduced from 100vh to avoid overflow */
        padding-top: 2rem;
        padding-bottom: 5rem;
        position: relative;
        z-index: 1;
    }

    .history-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05); /* Soft shadow */
        background: #fff;
        overflow: hidden;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eef2f7;
        padding: 1rem;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f2f5f8;
        color: #495057;
    }

    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 3px;
    }

    /* Animation */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="page-section">
    <div class="container-sm">
        
        <!-- Section Header -->
        <div class="section-header reveal text-center mb-5">
            <span class="section-badge">Riwayat</span>
            <h2 class="section-title">Pesanan Saya</h2>
            <p class="section-subtitle">Daftar riwayat perjalanan dan status pemesanan Anda</p>
        </div>

        <div class="history-card reveal">
            <div class="card-body p-0 table-responsive">

                <table class="table table-hover mb-0" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Tanggal Pesan</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th width="150" class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanan as $p)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#{{ $p->id }}</td>
                            <td>{{ $p->tanggal_pesan }}</td>
                            <td>{{ $p->tanggal_mulai }}</td>
                            <td>{{ $p->tanggal_selesai }}</td>
                            <td class="fw-bold">Rp {{ number_format($p->total_harga) }}</td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $p->status_badge }} px-3 py-2">
                                    @if ($p->status_tampilan === 'Pengembalian Bermasalah')
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @endif
                                    {{ $p->status_tampilan }}
                                </span>
                            </td>

                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-outline-info btn-sm rounded-pill px-3 me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detail{{ $p->id }}">
                                        Detail
                                    </button>

                                    @if (
                                        $p->status_tampilan === 'Sedang Disewa' &&
                                        !$p->pengembalian
                                    )
                                        <button class="btn btn-warning btn-sm rounded-pill px-3 text-white"
                                            data-bs-toggle="modal"
                                            data-bs-target="#pengembalian{{ $p->id }}">
                                            Kembalikan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fas fa-receipt fa-3x"></i>
                                </div>
                                <h5 class="text-muted">Belum ada pesanan</h5>
                                <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm mt-2 rounded-pill px-4">
                                    Sewa Mobil Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $pesanan->links() }}
        </div>

    </div>
</div>

{{-- MODALS OUTSIDE OF MAIN CONTAINER TO PREVENT Z-INDEX ISSUES --}}
@foreach ($pesanan as $p)
    <!-- MODAL PENGEMBALIAN -->
    <div class="modal fade" id="pengembalian{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pengembalian.storeUser') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Ajukan Pengembalian Mobil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- hidden pemesanan -->
                        <input type="hidden" name="pemesanan_id" value="{{ $p->id }}">
                        
                        <div class="alert alert-light border mb-3">
                            <small class="text-muted d-block">Pemesanan #{{ $p->id }}</small>
                            <strong>{{ $p->details->first()->mobil->merk ?? 'Mobil' }} {{ $p->details->first()->mobil->model ?? '' }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tanggal Kembali</label>
                            <input type="date"
                            class="form-control"
                            name="tanggal_kembali"
                            value="{{ date('Y-m-d') }}"
                            readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Kondisi Mobil</label>
                            <select name="kondisi_mobil" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="baik">Baik</option>
                                <option value="lecet">Lecet</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3"
                            placeholder="Contoh: ada goresan kecil di bumper"></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            Kirim Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div class="modal fade" id="detail{{ $p->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pemesanan #{{ $p->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-0">
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Status</span>
                            <span class="badge bg-{{ $p->status_badge }}">{{ $p->status_tampilan }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Harga</span>
                            <span class="fw-bold text-primary">Rp {{ number_format($p->total_harga) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Jadwal</small>
                        <div class="row mt-2">
                            <div class="col-6">
                                <small class="d-block text-muted">Ambil</small>
                                <strong>{{ $p->tanggal_mulai }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="d-block text-muted">Kembali</small>
                                <strong>{{ $p->tanggal_selesai }}</strong>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Armada</small>
                        @foreach ($p->details as $d)
                            <div class="d-flex align-items-center mt-2">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary">
                                    <i class="fas fa-car-side"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $d->mobil->merk ?? '-' }} {{ $d->mobil->model ?? '-' }}</div>
                                    <small class="text-muted">{{ $d->mobil->nomor_plat ?? '-' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    }, { threshold: 0.1 });

    reveals.forEach(el => observer.observe(el));
});
</script>

@endsection
