@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-file-earmark-pdf"></i>
    <h2 style="margin: 0;">Laporan Sistem</h2>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="admin-card text-center">
            <div class="mb-3">
                <i class="bi bi-cart-check" style="font-size: 48px; color: #667eea;"></i>
            </div>
            <h4>Laporan Pemesanan</h4>
            <p class="text-muted">Export data semua pemesanan mobil termasuk status dan total harga.</p>
            <a href="{{ route('admin.laporan.export-pesanan') }}" class="btn btn-primary w-100 mt-3">
                <i class="bi bi-download"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card text-center">
            <div class="mb-3">
                <i class="bi bi-credit-card" style="font-size: 48px; color: #48bb78;"></i>
            </div>
            <h4>Laporan Pembayaran</h4>
            <p class="text-muted">Export data transaksi pembayaran yang telah dilakukan oleh pengguna.</p>
            <a href="{{ route('admin.laporan.export-pembayaran') }}" class="btn btn-success w-100 mt-3">
                <i class="bi bi-download"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="admin-card text-center">
            <div class="mb-3">
                <i class="bi bi-arrow-return-left" style="font-size: 48px; color: #ed8936;"></i>
            </div>
            <h4>Laporan Pengembalian</h4>
            <p class="text-muted">Export data pengembalian mobil beserta kondisi dan catatan petugas.</p>
            <a href="{{ route('admin.laporan.export-pengembalian') }}" class="btn btn-warning w-100 mt-3" style="color: white;">
                <i class="bi bi-download"></i> Export PDF
            </a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="admin-card">
            <h5><i class="bi bi-info-circle"></i> Petunjuk Penggunaan</h5>
            <ul class="mb-0 mt-3">
                <li>Klik tombol <strong>Export PDF</strong> pada masing-masing kategori untuk mendownload laporan dalam format PDF.</li>
                <li>Laporan yang dihasilkan berisi data terbaru yang ada dalam sistem.</li>
                <li>Laporan ini dapat digunakan sebagai arsip atau bukti transaksi rental mobil.</li>
            </ul>
        </div>
    </div>
</div>

@endsection
