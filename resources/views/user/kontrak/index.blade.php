@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>Kontrak Sewa Saya
        </h4>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-house"></i> Dashboard
        </a>
    </div>

    @if($kontrak->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-x" style="font-size:64px; color:#ddd;"></i>
            <h5 class="mt-3 text-muted">Belum Ada Kontrak</h5>
            <p class="text-muted">Kontrak akan otomatis dibuat setelah pemesanan Anda disetujui.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-car-front me-2"></i>Lihat Katalog Mobil
            </a>
        </div>
    @else
        <div class="row g-3">
            @foreach($kontrak as $k)
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:50px;height:50px;">
                                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-primary">{{ $k->nomor_kontrak }}</div>
                                        <small class="text-muted">Dibuat: {{ $k->created_at->format('d F Y') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted mb-1">Kendaraan</div>
                                @foreach($k->pemesanan->details as $d)
                                    <span class="badge bg-light text-dark border me-1">
                                        {{ $d->mobil->merk ?? '?' }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="col-md-2">
                                <div class="small text-muted mb-1">Periode</div>
                                <div class="small fw-semibold">
                                    {{ $k->tanggal_mulai->format('d/m/Y') }}<br>
                                    s/d {{ $k->tanggal_selesai->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">{{ $k->durasi_sewa }} hari</small>
                            </div>
                            <div class="col-md-1">
                                <span class="badge
                                    @if($k->status === 'aktif') bg-primary
                                    @elseif($k->status === 'selesai') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($k->status) }}
                                </span>
                            </div>
                            <div class="col-md-2 text-md-end mt-2 mt-md-0">
                                <a href="{{ route('kontrak.download', $k->id) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="bi bi-file-pdf me-1"></i>Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $kontrak->links() }}
        </div>
    @endif
</div>
@endsection
