@extends('layouts.petugas')

@section('content')

<div class="page-title">
    <i class="bi bi-calendar-check"></i>
    <h2 style="margin: 0;">Kelola Pemesanan</h2>
</div>

<div class="petugas-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Pemesanan</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead style="background-color: #f5f7fa;">
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Tgl Pesan</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pemesanan as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status_badge }}">
                            {{ $p->status_tampilan }}
                        </span>
                    </td>
                    <td>
                        <!-- DETAIL -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#detail{{ $p->id }}">
                            Detail
                        </button>

                        <!-- KONFIRMASI -->
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#konfirmasi{{ $p->id }}">
                            Konfirmasi
                        </button>
                    </td>
                </tr>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="detail{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Pemesanan #{{ $p->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Pelanggan:</strong> {{ $p->user->name }}</p>
                                <p><strong>Email:</strong> {{ $p->user->email }}</p>
                                <p><strong>No HP:</strong> {{ $p->user->no_hp ?? '-' }}</p>
                                <p><strong>Tanggal Pesan:</strong> {{ $p->tanggal_pesan }}</p>
                                <p><strong>Tanggal Mulai:</strong> {{ $p->tanggal_mulai }}</p>
                                <p><strong>Tanggal Selesai:</strong> {{ $p->tanggal_selesai }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($p->status) }}</p>

                                <hr>

                                <h6>Mobil yang Dipesan:</h6>
                                @foreach ($p->details as $d)
                                    <p><strong>Model:</strong> {{ $d->mobil->model ?? '-' }}</p>
                                    <p><strong>Nomor Plat:</strong> {{ $d->mobil->nomor_plat ?? '-' }}</p>
                                    <p><strong>Lama Sewa:</strong> {{ $d->lama_sewa }} hari</p>
                                @endforeach

                                <hr>

                                <p class="fw-bold">
                                    Total Harga: Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL KONFIRMASI -->
                <div class="modal fade" id="konfirmasi{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('petugas.pemesanan.konfirmasi', $p->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Pemesanan #{{ $p->id }}</h5>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Status Pemesanan</label>
                                        <select name="status" class="form-control" required>
                                            <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="disetujui" {{ $p->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>

                                    <div class="alert alert-info">
                                        <small>
                                            <strong>Catatan:</strong><br>
                                            - Pending: Menunggu konfirmasi<br>
                                            - Disetujui: Pemesanan telah disetujui<br>
                                            - Ditolak: Pemesanan ditolak<br>
                                            - Selesai: Pemesanan telah selesai
                                        </small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="8" class="text-center">Data pemesanan belum tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $pemesanan->links() }}
    </div>
</div>

@endsection
