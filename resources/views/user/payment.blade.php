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
                                    <div class="alert alert-warning">Midtrans tidak tersedia. Gunakan pembayaran tunai.</div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="tab-tunai">
                                @include('partials.cash-payment-form', ['pemesanan' => $pemesanan])
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
