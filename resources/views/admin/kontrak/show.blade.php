@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="page-title mb-0">
        <i class="bi bi-file-earmark-text"></i> Detail Kontrak
    </h4>
    <a href="{{ route('admin.kontrak.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    {{-- KIRI: Detail Kontrak --}}
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-hash"></i> {{ $kontrak->nomor_kontrak }}
                </h5>
                <span class="badge fs-6
                    @if($kontrak->status === 'aktif') bg-primary
                    @elseif($kontrak->status === 'selesai') bg-success
                    @else bg-danger @endif">
                    {{ ucfirst($kontrak->status) }}
                </span>
            </div>

            <table class="table table-sm table-borderless">
                <tr>
                    <td class="text-muted" style="width:35%">Tanggal Kontrak</td>
                    <td><strong>{{ $kontrak->tanggal_kontrak->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Mulai Sewa</td>
                    <td><strong>{{ $kontrak->tanggal_mulai->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Selesai Sewa</td>
                    <td><strong>{{ $kontrak->tanggal_selesai->format('d F Y') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Durasi Sewa</td>
                    <td><strong>{{ $kontrak->durasi_sewa }} hari</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Total Harga</td>
                    <td class="text-success fs-5">
                        <strong>Rp {{ number_format($kontrak->total_harga, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                @if($kontrak->catatan)
                <tr>
                    <td class="text-muted">Catatan</td>
                    <td>{{ $kontrak->catatan }}</td>
                </tr>
                @endif
            </table>
        </div>

        {{-- Detail Kendaraan --}}
        <div class="admin-card mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-car-front text-primary me-2"></i>Kendaraan yang Disewa</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kendaraan</th>
                            <th>Plat Nomor</th>
                            <th>Lama Sewa</th>
                            <th>Harga/Hari</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kontrak->pemesanan->details as $detail)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $detail->mobil->merk ?? '-' }}</div>
                                <small class="text-muted">{{ $detail->mobil->kategori->nama_kategori ?? '' }}</small>
                            </td>
                            <td><span class="badge bg-dark">{{ $detail->mobil->nomor_plat ?? '-' }}</span></td>
                            <td>{{ $detail->lama_sewa }} hari</td>
                            <td>Rp {{ number_format($detail->mobil->harga_sewa ?? 0, 0, ',', '.') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($kontrak->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- KANAN: Info Customer & Aksi --}}
    <div class="col-lg-4">
        {{-- Info Customer --}}
        <div class="admin-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>Data Customer</h6>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;color:white;font-size:20px;font-weight:bold;">
                    {{ strtoupper(substr($kontrak->pemesanan->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold">{{ $kontrak->pemesanan->user->name ?? '-' }}</div>
                    <small class="text-muted">{{ $kontrak->pemesanan->user->email ?? '' }}</small>
                </div>
            </div>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted small">Telepon</td>
                    <td class="small">{{ $kontrak->pemesanan->user->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted small">ID Pemesanan</td>
                    <td><a href="{{ route('pemesanan.show', $kontrak->pemesanan->id) }}">#{{ $kontrak->pemesanan->id }}</a></td>
                </tr>
            </table>
        </div>

        {{-- Info Pembayaran --}}
        <div class="admin-card mb-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-credit-card text-success me-2"></i>Pembayaran</h6>
            @if($kontrak->pemesanan->pembayaran)
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small">Metode</td>
                        <td class="small fw-semibold">{{ $kontrak->pemesanan->pembayaran->metode_pembayaran }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Tanggal Bayar</td>
                        <td class="small">{{ \Carbon\Carbon::parse($kontrak->pemesanan->pembayaran->tanggal_bayar)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Jumlah</td>
                        <td class="small fw-semibold text-success">Rp {{ number_format($kontrak->pemesanan->pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Status</td>
                        <td>
                            <span class="badge {{ $kontrak->pemesanan->pembayaran->status === 'valid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($kontrak->pemesanan->pembayaran->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            @else
                <p class="text-muted small mb-0"><i class="bi bi-exclamation-circle text-warning"></i> Belum ada pembayaran</p>
            @endif
        </div>

        {{-- AKSI --}}
        <div class="admin-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-gear text-secondary me-2"></i>Aksi</h6>

            {{-- Download PDF --}}
            <a href="{{ route('kontrak.download', $kontrak->id) }}" class="btn btn-success w-100 mb-2">
                <i class="bi bi-file-pdf me-2"></i> Download Kontrak PDF
            </a>

            {{-- Update Status --}}
            <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="collapse" data-bs-target="#formStatus">
                <i class="bi bi-pencil-square me-2"></i> Ubah Status Kontrak
            </button>
            <div class="collapse" id="formStatus">
                <form action="{{ route('admin.kontrak.update', $kontrak->id) }}" method="POST" class="mt-2">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="aktif"      {{ $kontrak->status === 'aktif'      ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai"    {{ $kontrak->status === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $kontrak->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <textarea name="catatan" class="form-control form-control-sm" rows="2"
                            placeholder="Catatan (opsional)">{{ $kontrak->catatan }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Simpan Perubahan</button>
                </form>
            </div>

            <hr>

            {{-- Hapus --}}
            <form action="{{ route('admin.kontrak.destroy', $kontrak->id) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus kontrak ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="bi bi-trash me-2"></i> Hapus Kontrak
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
