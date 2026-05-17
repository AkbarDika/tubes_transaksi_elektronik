@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="page-title mb-0">
        <i class="bi bi-file-earmark-text"></i> Manajemen Kontrak
    </h4>
    <a href="{{ url('/pemesanan') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-list-ul"></i> Lihat Pemesanan
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- FILTER & SEARCH --}}
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.kontrak.index') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Cari Nomor Kontrak / Nama Customer</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari..."
                    value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted">Filter Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="aktif"      {{ request('status') == 'aktif'      ? 'selected' : '' }}>Aktif</option>
                <option value="selesai"    {{ request('status') == 'selesai'    ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.kontrak.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Kontrak</h5>
        <span class="badge bg-secondary">Total: {{ $kontrak->total() }} kontrak</span>
    </div>

    @if($kontrak->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-earmark-x" style="font-size: 48px; color: #ddd;"></i>
            <p class="mt-3">Belum ada kontrak. Generate kontrak dari halaman <a href="{{ url('/pemesanan') }}">Pemesanan</a>.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nomor Kontrak</th>
                        <th>Customer</th>
                        <th>Kendaraan</th>
                        <th>Periode Sewa</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kontrak as $i => $k)
                    <tr>
                        <td class="text-muted small">{{ $kontrak->firstItem() + $i }}</td>
                        <td>
                            <span class="fw-semibold text-primary">{{ $k->nomor_kontrak }}</span>
                        </td>
                        <td>
                            <div>{{ $k->pemesanan->user->name ?? '-' }}</div>
                            <small class="text-muted">{{ $k->pemesanan->user->email ?? '' }}</small>
                        </td>
                        <td>
                            @foreach($k->pemesanan->details as $d)
                                <span class="badge bg-light text-dark border">
                                    {{ $d->mobil->merk ?? '?' }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <small>
                                <i class="bi bi-calendar-range text-primary"></i>
                                {{ $k->tanggal_mulai->format('d/m/Y') }} &rarr; {{ $k->tanggal_selesai->format('d/m/Y') }}
                            </small>
                            <br>
                            <small class="text-muted">{{ $k->durasi_sewa }} hari</small>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($k->total_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="badge
                                @if($k->status === 'aktif') bg-primary
                                @elseif($k->status === 'selesai') bg-success
                                @else bg-danger @endif">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $k->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.kontrak.show', $k->id) }}"
                                   class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('kontrak.download', $k->id) }}"
                                   class="btn btn-outline-success" title="Download PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="confirmDelete({{ $k->id }}, '{{ $k->nomor_kontrak }}')"
                                    title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-container mt-3">
            {{ $kontrak->links() }}
        </div>
    @endif
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle"></i> Hapus Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kontrak <strong id="deleteNomor"></strong>?</p>
                <p class="text-muted small">Tindakan ini tidak bisa dibatalkan.</p>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, nomor) {
    document.getElementById('deleteNomor').textContent = nomor;
    document.getElementById('deleteForm').action = '/kontrak/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
