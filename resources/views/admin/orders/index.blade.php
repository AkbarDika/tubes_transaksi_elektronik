@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-calendar-check"></i>
    <h2 style="margin: 0;">Kelola Pemesanan</h2>
</div>

<div class="admin-card">
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
                    <td>{{ $p->tanggal_pesan }}</td>
                    <td>{{ $p->tanggal_mulai }}</td>
                    <td>{{ $p->tanggal_selesai }}</td>
                    <td>Rp {{ number_format($p->total_harga) }}</td>
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

                        <!-- EDIT -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#edit{{ $p->id }}">
                            Edit
                        </button>

                        <!-- HAPUS -->
                        <form action="{{ route('pemesanan.destroy', $p->id) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus pemesanan ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="detail{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Pemesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p><strong>Pelanggan:</strong> {{ $p->user->name }}</p>
                                <p><strong>Tanggal Pesan:</strong> {{ $p->tanggal_pesan }}</p>
                                <p><strong>Tanggal Mulai:</strong> {{ $p->tanggal_mulai }}</p>
                                <p><strong>Tanggal Selesai:</strong> {{ $p->tanggal_selesai }}</p>
                                <p><strong>Status:</strong> {{ $p->status_tampilan }}</p>

                                <hr>

                                @foreach ($p->details as $d)
                                    <p><strong>Mobil:</strong> {{ $d->mobil->model ?? '-' }}</p>
                                    <p><strong>Nomor Plat:</strong> {{ $d->mobil->nomor_plat ?? '-' }}</p>
                                @endforeach

                                <hr>

                                <p class="fw-bold">
                                    Total Harga: Rp {{ number_format($p->total_harga) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $detail = $p->details->first();
                @endphp

                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('pemesanan.update', $p->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pemesanan</h5>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <input type="number"
                                        id="harga_{{ $p->id }}"
                                        value="{{ optional($detail)->harga_per_hari ?? 0 }}">

                                    <input type="date"
                                        name="tanggal_mulai"
                                        id="mulai_{{ $p->id }}"
                                        class="form-control mb-2"
                                        value="{{ $p->tanggal_mulai }}"
                                        onchange="setMinTanggalSelesai({{ $p->id }}); hitungTotal({{ $p->id }})">

                                    <input type="date"
                                        name="tanggal_selesai"
                                        id="selesai_{{ $p->id }}"
                                        class="form-control mb-2"
                                        value="{{ $p->tanggal_selesai }}"
                                        onchange="hitungTotal({{ $p->id }})">



                                    <input type="number"
                                        name="total_harga"
                                        id="total_{{ $p->id }}"
                                        class="form-control mb-2"
                                        value="{{ $p->total_harga }}"
                                        readonly>


                                    <select name="status" class="form-control">
                                        <option value="pending"
                                            {{ $p->status == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="selesai"
                                            {{ $p->status == 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-success">Update</button>
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
    <div class="pagination-container">
        {{ $pemesanan->links() }}
    </div>
</div>

<script>
function hitungTotal(id) {
    const mulaiEl   = document.getElementById('mulai_' + id);
    const selesaiEl = document.getElementById('selesai_' + id);
    const hargaEl   = document.getElementById('harga_' + id);
    const totalEl   = document.getElementById('total_' + id);

    if (!mulaiEl.value || !selesaiEl.value) return;

    const mulai   = new Date(mulaiEl.value);
    const selesai = new Date(selesaiEl.value);
    const harga   = parseInt(hargaEl.value || 0);

    if (selesai < mulai || harga === 0) return;

    const selisihHari =
        Math.ceil((selesai - mulai) / (1000 * 60 * 60 * 24));

    totalEl.value = selisihHari * harga;
}
</script>
<script>
function setMinTanggalSelesai(id) {
    const mulaiEl   = document.getElementById('mulai_' + id);
    const selesaiEl = document.getElementById('selesai_' + id);

    if (!mulaiEl.value) return;

    const mulai = new Date(mulaiEl.value);

    // +1 hari dari tanggal mulai
    mulai.setDate(mulai.getDate() + 1);

    const minDate = mulai.toISOString().split('T')[0];

    selesaiEl.min = minDate;

    // Kalau tanggal selesai masih <= tanggal mulai → reset
    if (selesaiEl.value && selesaiEl.value < minDate) {
        selesaiEl.value = minDate;
    }
}
</script>
<script>
document.addEventListener('shown.bs.modal', function (e) {
    if (e.target.id.startsWith('edit')) {
        const id = e.target.id.replace('edit', '');
        setMinTanggalSelesai(id);
    }
});
</script>



@endsection
