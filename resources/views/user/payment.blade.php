@extends('layouts.app')

@section('content')
<section class="py-5" style="background: linear-gradient(to bottom, #ffffff 0%, #ebf5ff 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h3 class="card-title fw-bold mb-2">
                            <i class="bi bi-credit-card"></i> Pembayaran Pesanan #{{ $pemesanan->id }}
                        </h3>
                        <p class="text-muted mb-4">Pilih metode: Midtrans (online) atau tunai</p>

                        <div class="bg-light p-4 rounded mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Tagihan</span>
                                <h4 class="fw-bold mb-0 text-primary">
                                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-midtrans" type="button">
                                    <i class="bi bi-phone"></i> Midtrans
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tunai" type="button">
                                    <i class="bi bi-cash"></i> Tunai
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-midtrans">
                                @if($snapToken)
                                    <div class="alert alert-info mb-3">
                                        <small>Transfer bank, e-wallet, QRIS, dan lainnya via Midtrans.</small>
                                    </div>
                                    <button id="pay-button" type="button" class="btn btn-success btn-lg w-100 fw-bold">
                                        <i class="bi bi-lock"></i> Bayar via Midtrans
                                    </button>
                                @else
                                    <div class="alert alert-warning">
                                        <strong>Midtrans tidak tersedia saat ini.</strong><br>
                                        <small>Gunakan pembayaran tunai atau coba lagi nanti.</small>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="tab-tunai">
                                <div class="text-center p-4 border rounded bg-light">
                                    <div class="mb-3">
                                        <i class="fa-solid fa-cash-register fs-1 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Simulasi Pembayaran Tunai (POS)</h5>
                                    
                                    @if($pemesanan->pembayaran && $pemesanan->pembayaran->status === 'menunggu')
                                        <div class="alert alert-warning border-warning my-3">
                                            <i class="fa-solid fa-spinner fa-spin me-2"></i>
                                            <strong>Status: Sedang dalam Proses Pengecekan Kasir</strong>
                                            <p class="small text-muted mb-0 mt-1">
                                                Silakan datangi petugas kasir kami untuk memproses verifikasi fisik uang. Petugas akan mengunggah gambar uang dan menekan konfirmasi pembayaran pada menu POS.
                                            </p>
                                        </div>
                                    @elseif($pemesanan->pembayaran && $pemesanan->pembayaran->status === 'valid')
                                        <div class="alert alert-success border-success my-3">
                                            <i class="fa-solid fa-circle-check me-2"></i>
                                            <strong>Status: Pembayaran Tunai Lunas & Valid</strong>
                                            @if($pemesanan->pembayaran->kembalian > 0)
                                                <p class="small mb-0 mt-1">Kembalian: Rp {{ number_format($pemesanan->pembayaran->kembalian, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    @elseif($pemesanan->pembayaran && $pemesanan->pembayaran->status === 'ditolak')
                                        <div class="alert alert-danger border-danger my-3">
                                            <i class="fa-solid fa-circle-xmark me-2"></i>
                                            <strong>Status: Pembayaran Tunai Ditolak</strong>
                                            <p class="small text-muted mb-0 mt-1">Silakan ajukan pengecekan ulang atau hubungi kasir.</p>
                                        </div>
                                        <form action="{{ route('pemesanan.payment.cash', $pemesanan) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-md fw-bold w-100">
                                                <i class="fa-solid fa-redo me-1"></i> Ajukan Ulang Pengecekan Tunai
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-muted small mb-3">
                                            Gunakan simulasi ini untuk mengajukan verifikasi pembayaran tunai kepada kasir.
                                        </p>
                                        <form action="{{ route('pemesanan.payment.cash', $pemesanan) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-lg fw-bold w-100 mb-3">
                                                <i class="fa-solid fa-circle-play me-1"></i> Mulai Proses Pengecekan
                                            </button>
                                        </form>
                                    @endif

                                    <div class="bg-white p-3 rounded shadow-sm d-inline-block border w-100 mt-2">
                                        <span class="text-muted d-block small">Nomor Pemesanan Anda:</span>
                                        <span class="fs-4 fw-bold text-dark">#{{ $pemesanan->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('user.riwayat_pesanan') }}" class="btn btn-outline-secondary btn-sm w-100 mt-4">
                            Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($snapToken ?? false)
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function() {
            window.location.href = '{{ route("pemesanan.success", $pemesanan->id) }}';
        },
        onPending: function() {
            alert('Pembayaran sedang diproses. Tunggu konfirmasi.');
        },
        onError: function() {
            window.location.href = '{{ route("pemesanan.failed", $pemesanan->id) }}';
        },
        onClose: function() {
            alert('Dialog pembayaran ditutup.');
        }
    });
});
</script>
@endif
@endsection
