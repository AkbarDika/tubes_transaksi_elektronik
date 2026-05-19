@extends('layouts.petugas')

@section('content')

<div class="page-title">
    <i class="bi bi-cash-stack"></i>
    <h2 style="margin: 0;">POS — Pemesanan #{{ $pemesanan->id }}</h2>
</div>

<div class="row mt-4">
    <div class="col-lg-5 mb-4">
        <div class="petugas-card">
            <h5 class="fw-bold mb-3">Detail Pemesanan</h5>
            <p><strong>Pelanggan:</strong> {{ $pemesanan->user->name }}</p>
            <p><strong>Email:</strong> {{ $pemesanan->user->email }}</p>
            <p><strong>Periode:</strong> {{ $pemesanan->tanggal_mulai->format('d/m/Y') }} — {{ $pemesanan->tanggal_selesai->format('d/m/Y') }}</p>
            <hr>
            @foreach($pemesanan->details as $d)
                <p class="mb-1"><strong>Mobil:</strong> {{ $d->mobil->merk ?? '' }} {{ $d->mobil->model ?? '' }} ({{ $d->mobil->nomor_plat ?? '-' }})</p>
            @endforeach
            <hr>
            <p class="fs-4 fw-bold text-success mb-0">
                Total: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
            </p>
        </div>
        <a href="{{ route('petugas.pos.index') }}" class="btn btn-outline-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar POS
        </a>
    </div>

    <div class="col-lg-7">
        <div class="petugas-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-cash-coin"></i> Validasi Uang Tunai</h5>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @include('partials.cash-payment-form', [
                'pemesanan' => $pemesanan,
                'formAction' => route('petugas.pos.bayar', $pemesanan),
                'submitLabel' => 'Proses Pembayaran di Kasir',
            ])

            <div class="alert alert-info mt-3 mb-0 small">
                <i class="bi bi-shield-check"></i>
                <strong>Secure by design:</strong> total tagihan dihitung dari database;
                validasi uang &ge; tagihan dilakukan di server; transaksi dikunci agar tidak double payment.
            </div>
        </div>
    </div>
</div>

@endsection
