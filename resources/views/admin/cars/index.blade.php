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
                    <th>Foto</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Model</th>
                    <th>Tahun</th>
                    <th>Plat</th>
                    <th>Harga Sewa</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mobils as $mobil)
                <tr>
                    <td>{{ $mobil->id }}</td>
                    <td>
                        @if ($mobil->foto)
                            <img src="{{ asset('storage/'.$mobil->foto) }}" width="80">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $mobil->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $mobil->merk }}</td>
                    <td>{{ $mobil->model }}</td>
                    <td>{{ $mobil->tahun }}</td>
                    <td>{{ $mobil->nomor_plat }}</td>
                    <td>Rp {{ number_format($mobil->harga_sewa) }}</td>
                    <td>
                        <span class="badge {{ $mobil->status == 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($mobil->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#edit{{ $mobil->id }}">
                            Edit
                        </button>

                        <!-- Hapus -->
                        <form action="{{ route('mobil.destroy', $mobil->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus mobil ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div class="modal fade" id="edit{{ $mobil->id }}" tabindex="-1">
                    <div class="modal-dialog">
                <form action="{{ route('mobil.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Mobil</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <select name="kategori_id" class="form-control mb-2" required>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}"
                                                {{ $mobil->kategori_id == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="merk" class="form-control mb-2"
                                        value="{{ $mobil->merk }}" required>

                                    <input type="text" name="model" class="form-control mb-2"
                                        value="{{ $mobil->model }}" required>

                                    <input type="number" name="tahun" class="form-control mb-2"
                                        value="{{ $mobil->tahun }}" required>

                                    <input type="text" name="nomor_plat" class="form-control mb-2"
                                        value="{{ $mobil->nomor_plat }}" required>

                                    <input type="number" name="harga_sewa" class="form-control mb-2"
                                        value="{{ $mobil->harga_sewa }}" required>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Foto Mobil</label>
                                        @if ($mobil->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $mobil->foto) }}" width="100" class="img-thumbnail">
                                            </div>
                                        @endif
                                        <input type="file" name="foto" class="form-control">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                                    </div>

                                    <select name="status" class="form-control">
                                        <option value="tersedia"
                                            {{ $mobil->status == 'tersedia' ? 'selected' : '' }}>
                                            Tersedia
                                        </option>
                                        <option value="disewa"
                                            {{ $mobil->status == 'disewa' ? 'selected' : '' }}>
                                            Disewa
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
                    <td colspan="9" class="text-center">Data mobil belum tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-container">
        {{ $mobils->links() }}
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('mobil.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mobil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="kategori_id" class="form-control mb-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="merk" class="form-control mb-2" placeholder="Merk" required>
                    <input type="text" name="model" class="form-control mb-2" placeholder="Model" required>
                    <input type="number" name="tahun" class="form-control mb-2" placeholder="Tahun" required>
                    <input type="text" name="nomor_plat" class="form-control mb-2" placeholder="Plat" required>
                    <input type="number" name="harga_sewa" class="form-control mb-2" placeholder="Harga Sewa" required>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">Foto Mobil</label>
                        <input type="file" name="foto" class="form-control" required>
                    </div>

                    <select name="status" class="form-control">
                        <option value="tersedia">Tersedia</option>
                        <option value="disewa">Disewa</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
