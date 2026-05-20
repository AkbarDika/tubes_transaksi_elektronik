@extends('layouts.petugas')

@section('content')

<div class="page-title">
    <i class="bi bi-cash-register"></i>
    <h2 style="margin: 0;">POS — Pembayaran Tunai</h2>
</div>

<p class="text-muted mb-4">Pemesanan yang sudah disetujui dan menunggu pembayaran di kasir.</p>

<div class="petugas-card mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small">Cari ID / Nama</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ID pemesanan atau nama pelanggan">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i> Cari</button>
            <a href="{{ route('petugas.pos.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="petugas-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead style="background:#f5f7fa;">
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Tgl Mulai</th>
                    <th>Status Pengecekan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemesanan as $p)
                <tr>
                    <td class="fw-bold">#{{ $p->id }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $p->tanggal_mulai->format('d M Y') }}</td>
                    <td>
                        @if($p->pembayaran && $p->pembayaran->status === 'menunggu')
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Menunggu Pengecekan</span>
                        @elseif($p->pembayaran && $p->pembayaran->status === 'ditolak')
                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Pembayaran Ditolak</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-cash-stack"></i> Belum Diajukan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('petugas.pos.show', $p) }}" class="btn btn-warning btn-sm text-dark fw-bold">
                            <i class="bi bi-cash"></i> Bayar/Cek
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Tidak ada pemesanan menunggu pembayaran tunai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $pemesanan->links() }}
</div>

@endsection
