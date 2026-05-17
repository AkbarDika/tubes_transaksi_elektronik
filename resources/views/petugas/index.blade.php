@extends('layouts.petugas')

@section('content')

<div class="page-title">
    <i class="bi bi-graph-up"></i>
    <h2 style="margin: 0;">Dashboard Petugas</h2>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="petugas-stat-box stat-primary">
            <div style="text-align: center;">
                <i class="bi bi-clock-history" style="font-size: 32px; color: #48bb78;"></i>
            </div>
            <h3>{{ $totalPemesananPending }}</h3>
            <p>Pemesanan Pending</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="petugas-stat-box stat-success">
            <div style="text-align: center;">
                <i class="bi bi-check-circle" style="font-size: 32px; color: #38a169;"></i>
            </div>
            <h3>{{ $totalPemesananDisetujuiHariIni }}</h3>
            <p>Disetujui Hari Ini</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="petugas-stat-box stat-warning">
            <div style="text-align: center;">
                <i class="bi bi-arrow-return-left" style="font-size: 32px; color: #ed8936;"></i>
            </div>
            <h3>{{ $totalPengembalianPending }}</h3>
            <p>Pengembalian Pending</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="petugas-stat-box stat-danger">
            <div style="text-align: center;">
                <i class="bi bi-check2-all" style="font-size: 32px; color: #f56565;"></i>
            </div>
            <h3>{{ $totalPengembalianSelesaiHariIni }}</h3>
            <p>Pengembalian Selesai Hari Ini</p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-6">
        <div class="petugas-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h5 style="margin: 0; font-weight: bold;">
                    <i class="bi bi-calendar-check"></i> Pemesanan Terbaru
                </h5>
                <a href="{{ route('petugas.pemesanan') }}" class="btn btn-sm btn-success">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f5f7fa;">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesananTerbaru as $p)
                        <tr>
                            <td>#{{ $p->id }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status_badge }}">
                                    {{ $p->status_tampilan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Returns -->
    <div class="col-lg-6">
        <div class="petugas-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h5 style="margin: 0; font-weight: bold;">
                    <i class="bi bi-arrow-return-left"></i> Pengembalian Terbaru
                </h5>
                <a href="{{ route('petugas.pengembalian') }}" class="btn btn-sm btn-success">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f5f7fa;">
                        <tr>
                            <th>ID</th>
                            <th>Pemesanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalianTerbaru as $pg)
                        <tr>
                            <td>#{{ $pg->id_pengembalian }}</td>
                            <td>Pemesanan #{{ $pg->pemesanan_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($pg->tanggal_kembali)->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $pg->status_pengembalian == 'pending' ? 'warning' : ($pg->status_pengembalian == 'selesai' ? 'success' : 'danger') }}">
                                    {{ ucfirst($pg->status_pengembalian) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
