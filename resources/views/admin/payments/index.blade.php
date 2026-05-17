@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-credit-card"></i>
    <h2 style="margin: 0;">Kelola Pembayaran</h2>
</div>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h5 style="margin: 0; font-weight: bold;">Daftar Pembayaran</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Pembayaran
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead style="background-color: #f5f7fa;">
                <tr>
                    <th>ID</th>
                    <th>Metode</th>
                    <th>Tanggal Bayar</th>
                    <th>Jumlah Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayaran as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->metode_pembayaran }}</td>
                    <td>{{ $p->tanggal_bayar }}</td>
                    <td>Rp {{ number_format($p->jumlah_bayar) }}</td>
                    <!-- <td>
                        @if ($p->bukti_bayar)
                            <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank">
                                <img src="{{ asset('storage/'.$p->bukti_bayar) }}"
                                    width="60" class="rounded">
                            </a>
                        @else
                            -
                        @endif
                    </td> -->

                    <td>
                        <span class="badge {{ $p->status == 'valid' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Edit -->
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#edit{{ $p->id }}">
                            Edit
                        </button>


                        <!-- Hapus -->
                        <form action="{{ route('pembayaran.destroy', $p->id) }}" method="POST"
                            class="d-inline form-hapus">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <!-- MODAL EDIT PEMBAYARAN -->
                <div class="modal fade" id="edit{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('pembayaran.update', $p->id) }}"
                            method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit Pembayaran</h5>
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

                                    <input type="text" name="metode_pembayaran"
                                        class="form-control mb-2"
                                        value="{{ $p->metode_pembayaran }}" required>

                                    <input type="date" name="tanggal_bayar"
                                        class="form-control mb-2"
                                        value="{{ $p->tanggal_bayar }}" required>

                                    <input type="number" name="jumlah_bayar"
                                        class="form-control mb-2"
                                        value="{{ $p->jumlah_bayar }}" required>

                                    <!-- PREVIEW BUKTI -->
                                    @if ($p->bukti_bayar)
                                        <div class="mb-2">
                                            <small>Bukti saat ini:</small><br>
                                            <img src="{{ asset('storage/'.$p->bukti_bayar) }}"
                                                width="80" class="rounded">
                                        </div>
                                    @endif

                                    <!-- GANTI BUKTI -->
                                    <input type="file" name="bukti_bayar"
                                        class="form-control mb-2"
                                        accept="image/*">

                                    <select name="status" class="form-control">
                                        <option value="menunggu" {{ $p->status == 'menunggu' ? 'selected' : '' }}>
                                            Menunggu
                                        </option>
                                        <option value="valid" {{ $p->status == 'valid' ? 'selected' : '' }}>
                                            Valid
                                        </option>
                                        <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
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
                    <td colspan="7" class="text-center">Data pembayaran belum ada</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $pembayaran->links() }}
        </div>
    </div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pembayaran.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Pembayaran</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="pemesanan_id" class="form-control mb-2" required>
                        <option value="">Pilih Pemesanan</option>
                        @foreach ($pemesanan as $psn)
                            <option value="{{ $psn->id }}">
                                Pemesanan #{{ $psn->id }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" name="metode_pembayaran"
                        class="form-control mb-2"
                        placeholder="Metode Pembayaran" required>

                    <input type="date" name="tanggal_bayar"
                        class="form-control mb-2" required>

                    <input type="number" name="jumlah_bayar"
                        class="form-control mb-2" required>

                    <!-- BUKTI BAYAR -->
                    <input type="file" name="bukti_bayar"
                        class="form-control mb-2"
                        accept="image/*" required>

                    <select name="status" class="form-control">
                        <option value="menunggu">Menunggu</option>
                        <option value="lunas">Valid</option>
                        <option value="ditolak">Ditolak</option>
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
