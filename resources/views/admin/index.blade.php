@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-graph-up"></i>
    <h2 style="margin: 0;">Dashboard Admin</h2>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="admin-stat-box stat-primary">
            <div style="text-align: center;">
                <i class="bi bi-car-front" style="font-size: 32px; color: #667eea;"></i>
            </div>
            <h3>{{ $totalMobil }}</h3>
            <p>Total Mobil</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="admin-stat-box stat-success">
            <div style="text-align: center;">
                <i class="bi bi-check-circle" style="font-size: 32px; color: #48bb78;"></i>
            </div>
            <h3>{{ $pemesananAktif }}</h3>
            <p>Pemesanan Aktif</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="admin-stat-box stat-warning">
            <div style="text-align: center;">
                <i class="bi bi-credit-card" style="font-size: 32px; color: #ed8936;"></i>
            </div>
            <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <p>Total Pendapatan</p>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="admin-stat-box stat-danger">
            <div style="text-align: center;">
                <i class="bi bi-people" style="font-size: 32px; color: #f56565;"></i>
            </div>
            <h3>{{ $totalPengguna }}</h3>
            <p>Total Pengguna</p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h5 style="margin: 0; font-weight: bold;">
                    <i class="bi bi-calendar-check"></i> Pemesanan Terbaru
                </h5>
                <a href="{{ route('pemesanan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f5f7fa;">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Mobil</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesananTerbaru as $pesanan)
                        <tr>
                            <td>#PES{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $pesanan->user->name }}</td>
                            <td>
                                @foreach($pesanan->details as $detail)
                                    {{ $detail->mobil->merk }} {{ $detail->mobil->model }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </td>
                            <td>{{ $pesanan->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $pesanan->status_badge }}">
                                    {{ $pesanan->status_tampilan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pemesanan terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="col-lg-4">
        <div class="admin-card">
            <h5 style="margin-bottom: 20px; font-weight: bold;">
                <i class="bi bi-lightning"></i> Aksi Cepat
            </h5>
            <a href="{{ route('mobil.index') }}" class="btn btn-outline-primary w-100 mb-2">
                <i class="bi bi-plus-circle"></i> Tambah Mobil Baru
            </a>
            <a href="{{ route('pemesanan.index') }}" class="btn btn-outline-primary w-100 mb-2">
                <i class="bi bi-pencil-square"></i> Kelola Pemesanan
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-primary w-100 mb-2">
                <i class="bi bi-file-earmark-pdf"></i> Buat Laporan
            </a>
            <a href="#" class="btn btn-outline-primary w-100">
                <i class="bi bi-gear"></i> Pengaturan Sistem
            </a>
        </div>

        <div class="admin-card">
            <h5 style="margin-bottom: 20px; font-weight: bold;">
                <i class="bi bi-info-circle"></i> Informasi Sistem
            </h5>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Versi Aplikasi:</strong> 1.0.0
            </p>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Database:</strong> MySQL
            </p>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Last Update:</strong> 06 Jan 2026
            </p>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Status:</strong> <span class="badge bg-success">Online</span>
            </p>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="admin-card">
            <h5 style="margin-bottom: 20px; font-weight: bold;">
                <i class="bi bi-graph-up"></i> Statistik Pendapatan ({{ date('Y') }})
            </h5>
            <div id="revenueChart" style="height: 350px;"></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card">
            <h5 style="margin-bottom: 20px; font-weight: bold;">
                <i class="bi bi-pie-chart"></i> Distribusi Mobil Terlaris
            </h5>
            <div id="popularityChart" style="height: 350px;"></div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    // Revenue Line Chart
    Highcharts.chart('revenueChart', {
        chart: {
            type: 'areaspline',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Jumlah (Rp)'
            },
            labels: {
                formatter: function () {
                    return 'Rp ' + Highcharts.numberFormat(this.value, 0, ',', '.');
                }
            }
        },
        tooltip: {
            shared: true,
            valuePrefix: 'Rp '
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.1,
                color: '#667eea',
                marker: {
                    radius: 4,
                    lineColor: '#667eea',
                    lineWidth: 2
                }
            }
        },
        series: [{
            name: 'Pendapatan',
            data: {!! json_encode($monthlyRevenue) !!}
        }]
    });

    // Popularity Pie Chart
    Highcharts.chart('popularityChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y} kali</b> ({point.percentage:.1f}%)'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.percentage:.1f}%',
                    distance: -30,
                    style: {
                        color: 'white',
                        textOutline: 'none'
                    }
                },
                showInLegend: true
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Disewa',
            colorByPoint: true,
            data: {!! json_encode($pieChartData) !!}
        }]
    });
</script>
@endpush

@endsection
