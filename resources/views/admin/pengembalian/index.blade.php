@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-car-front"></i>
    <h2 style="margin: 0;">Kelola Mobil</h2>
</div>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Mobil</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Mobil
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead style="background-color: #f5f7fa;">
                <tr>
                    <th>ID</th>
                    <th>Pemesanan ID</th>
                    <th>Tanggal Kembali</th>
                    <th>Kondi Mobil</th>
                    <th>Catatan</th>
                    <th>Status Pengembalian</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengembalian as $p)
                <tr>
                    <td>{{ $p->id_pengembalian }}</td>
                    <td>Pemesanan #{{ $p->pemesanan_id }}</td>
                    <td>{{ $p->tanggal_kembali }}</td>
                    <td>{{ ucfirst($p->kondisi_mobil) }}</td>
                    <td>{{ $p->catatan ?? '-' }}</td>
                    <td>
                        <span class="badge 
                            {{ $p->status_pengembalian == 'pending' ? 'bg-warning' : 
                            ($p->status_pengembalian == 'selesai' ? 'bg-success' : 'bg-danger') }}">
                            {{ ucfirst($p->status_pengembalian) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit{{ $p->id_pengembalian }}">
                            Edit
                        </button>

                        <form action="{{ route('pengembalian.destroy', $p->id_pengembalian) }}"
                            method="POST"
                            class="d-inline form-hapus">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <div class="modal fade" id="edit{{ $p->id_pengembalian }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('pengembalian.update', $p->id_pengembalian) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Pengembalian</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <select name="pemesanan_id" class="form-control mb-2" required>
                                        @foreach ($pemesanan as $psn)
                                            <option value="{{ $psn->id }}"
                                                {{ $p->pemesanan_id == $psn->id ? 'selected' : '' }}>
                                                Pemesanan #{{ $psn->id }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="date" name="tanggal_kembali"
                                        class="form-control mb-2"
                                        value="{{ $p->tanggal_kembali }}">

                                    <select name="kondisi_mobil" class="form-control mb-2">
                                        <option value="baik" {{ $p->kondisi_mobil=='baik'?'selected':'' }}>Baik</option>
                                        <option value="rusak" {{ $p->kondisi_mobil=='rusak'?'selected':'' }}>Rusak</option>
                                    </select>

                                    <textarea name="catatan" class="form-control mb-2">{{ $p->catatan }}</textarea>

                                    <select name="status_pengembalian" class="form-control">
                                        <option value="pending" {{ $p->status_pengembalian=='pending'?'selected':'' }}>Pending</option>
                                        <option value="selesai" {{ $p->status_pengembalian=='selesai'?'selected':'' }}>Selesai</option>
                                        <option value="bermasalah" {{ $p->status_pengembalian=='bermasalah'?'selected':'' }}>Bermasalah</option>
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
                    <td colspan="7" class="text-center">Data pengembalian belum ada</td>
                </tr>
                @endforelse
                </tbody>

        </table>
    </div>
    <div class="pagination-container">
        {{ $pengembalian->links() }}
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengembalian.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Pengembalian</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="pemesanan_id" class="form-control mb-2" required>
                        <option value="">Pilih Pemesanan</option>
                        @foreach ($pemesanan as $psn)
                            <option value="{{ $psn->id }}">Pemesanan #{{ $psn->id }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal_kembali"
                        class="form-control mb-2" required>

                    <select name="kondisi_mobil" class="form-control mb-2">
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>

                    <textarea name="catatan" class="form-control mb-2"
                        placeholder="Catatan (opsional)"></textarea>

                    <select name="status_pengembalian" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="selesai">Selesai</option>
                        <option value="bermasalah">Bermasalah</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}'
});
</script>
@endif

<script>
document.querySelectorAll('.form-hapus').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data pembayaran akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@endsection
