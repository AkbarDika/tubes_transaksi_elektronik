@extends('layouts.petugas')

@section('content')

<div class="page-title">
    <i class="bi bi-arrow-return-left"></i>
    <h2 style="margin: 0;">Kelola Pengembalian</h2>
</div>

<div class="petugas-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Daftar Pengembalian</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead style="background-color: #f5f7fa;">
                <tr>
                    <th>ID</th>
                    <th>Pemesanan ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Tgl Kembali</th>
                    <th>Kondisi Mobil</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengembalian as $pg)
                <tr>
                    <td>{{ $pg->id_pengembalian }}</td>
                    <td>Pemesanan #{{ $pg->pemesanan_id }}</td>
                    <td>{{ $pg->pemesanan->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pg->tanggal_kembali)->format('d M Y') }}</td>
                    <td>
                        @if($pg->kondisi_mobil == 'baik')
                            <span class="badge bg-success">Baik</span>
                        @else
                            <span class="badge bg-danger">Rusak</span>
                        @endif
                    </td>
                    <td>
                        @if($pg->status_pengembalian == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($pg->status_pengembalian == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-danger">Bermasalah</span>
                        @endif
                    </td>
                    <td>
                        <!-- DETAIL -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#detail{{ $pg->id_pengembalian }}">
                            Detail
                        </button>

                        <!-- KONFIRMASI -->
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#konfirmasi{{ $pg->id_pengembalian }}">
                            Konfirmasi
                        </button>
                    </td>
                </tr>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="detail{{ $pg->id_pengembalian }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Pengembalian #{{ $pg->id_pengembalian }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <h6>Info Pengembalian:</h6>
                                <p><strong>ID Pengembalian:</strong> {{ $pg->id_pengembalian }}</p>
                                <p><strong>Pemesanan ID:</strong> #{{ $pg->pemesanan_id }}</p>
                                <p><strong>Tanggal Kembali:</strong> {{ $pg->tanggal_kembali }}</p>
                                <p><strong>Kondisi Mobil:</strong> {{ ucfirst($pg->kondisi_mobil) }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($pg->status_pengembalian) }}</p>
                                <p><strong>Catatan:</strong> {{ $pg->catatan ?? '-' }}</p>

                                <hr>

                                <h6>Info Pelanggan:</h6>
                                <p><strong>Nama:</strong> {{ $pg->pemesanan->user->name ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $pg->pemesanan->user->email ?? '-' }}</p>
                                <p><strong>No HP:</strong> {{ $pg->pemesanan->user->no_hp ?? '-' }}</p>

                                <hr>

                                <h6>Info Mobil:</h6>
                                @foreach ($pg->pemesanan->details as $d)
                                    <p><strong>Model:</strong> {{ $d->mobil->model ?? '-' }}</p>
                                    <p><strong>Nomor Plat:</strong> {{ $d->mobil->nomor_plat ?? '-' }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL KONFIRMASI -->
                <div class="modal fade" id="konfirmasi{{ $pg->id_pengembalian }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('petugas.pengembalian.konfirmasi', $pg->id_pengembalian) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Pengembalian #{{ $pg->id_pengembalian }}</h5>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Status Pengembalian</label>
                                        <select name="status_pengembalian" class="form-control" id="status_pengembalian_{{ $pg->id_pengembalian }}" onchange="handleStatusChange({{ $pg->id_pengembalian }})" required>
                                            <option value="pending" {{ $pg->status_pengembalian == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="selesai" {{ $pg->status_pengembalian == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="bermasalah" {{ $pg->status_pengembalian == 'bermasalah' ? 'selected' : '' }}>Bermasalah</option>
                                        </select>
                                    </div>

                                    <!-- Section Denda (Hidden by default) -->
                                    <div id="denda_section_{{ $pg->id_pengembalian }}" style="display: none; background: #fff3cd; padding: 15px; border-radius: 5px; border: 1px solid #ffecb5;">
                                        <h6 class="text-warning fw-bold"><i class="bi bi-exclamation-triangle"></i> Detail Masalah</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Masalah</label>
                                            <select name="jenis_denda" id="jenis_denda_{{ $pg->id_pengembalian }}" class="form-control" onchange="handleJenisDendaChange({{ $pg->id_pengembalian }})">
                                                <option value="">-- Pilih Jenis Masalah --</option>
                                                <option value="telat">Telat Mengembalikan</option>
                                                <option value="masalah_unit">Masalah Unit / Kerusakan</option>
                                            </select>
                                        </div>

                                        <!-- Input Hari Telat -->
                                        <div class="mb-3" id="input_hari_telat_{{ $pg->id_pengembalian }}" style="display: none;">
                                            <label class="form-label">Jumlah Hari Telat</label>
                                            <div class="input-group">
                                                <input type="number" name="hari_telat" id="hari_telat_{{ $pg->id_pengembalian }}" class="form-control" min="1" oninput="calculateDendaTelat({{ $pg->id_pengembalian }})">
                                                <span class="input-group-text">Hari</span>
                                            </div>
                                            <small class="text-muted">Denda: Rp 100.000 / hari</small>
                                        </div>

                                        <!-- Input Keterangan Kerusakan -->
                                        <div class="mb-3" id="input_keterangan_{{ $pg->id_pengembalian }}" style="display: none;">
                                            <label class="form-label">Keterangan Kerusakan</label>
                                            <textarea
                                                name="keterangan_denda"
                                                id="keterangan_denda_{{ $pg->id_pengembalian }}"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Jelaskan kerusakan mobil...">
                                            </textarea>
                                        </div>

                                        <!-- Input Total Denda -->
                                        <div class="mb-3" id="input_total_denda_{{ $pg->id_pengembalian }}" style="display: none;">
                                            <label class="form-label fw-bold">Total Denda (Rp)</label>
                                            <input type="number" name="total_denda" id="total_denda_{{ $pg->id_pengembalian }}" class="form-control fw-bold" min="0">
                                            <small class="text-muted" id="note_auto_calc_{{ $pg->id_pengembalian }}" style="display:none;">*Otomatis dihitung dari hari telat</small>
                                        </div>
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
                    <td colspan="7" class="text-center">Data pengembalian belum tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $pengembalian->links() }}
    </div>
</div>


<script>
    function handleStatusChange(id) {
        var status = document.getElementById('status_pengembalian_' + id).value;
        var dendaSection = document.getElementById('denda_section_' + id);
        var jenisDendaSelect = document.getElementById('jenis_denda_' + id);
        
        if (status === 'bermasalah') {
            dendaSection.style.display = 'block';
            jenisDendaSelect.setAttribute('required', 'required');
            document.getElementById('total_denda_' + id).value = '';
            document.getElementById('hari_telat_' + id).value = '';
            document.getElementById('keterangan_denda_' + id).value = '';

        } else {
            dendaSection.style.display = 'none';
            jenisDendaSelect.removeAttribute('required');
            // Reset fields
            jenisDendaSelect.value = "";
            handleJenisDendaChange(id);
        }
    }

    function handleJenisDendaChange(id) {
        var jenis = document.getElementById('jenis_denda_' + id).value;
        var divTelat = document.getElementById('input_hari_telat_' + id);
        var divKet = document.getElementById('input_keterangan_' + id);
        var divTotal = document.getElementById('input_total_denda_' + id);
        var inputTotal = document.getElementById('total_denda_' + id);
        var noteAuto = document.getElementById('note_auto_calc_' + id);
        
        // Hide all first
        divTelat.style.display = 'none';
        divKet.style.display = 'none';
        divTotal.style.display = 'none';
        inputTotal.removeAttribute('readonly');
        inputTotal.removeAttribute('required');
        noteAuto.style.display = 'none';

        if (jenis === 'telat') {
            divTelat.style.display = 'block';
            divTotal.style.display = 'block';
            inputTotal.setAttribute('readonly', 'readonly');
            inputTotal.setAttribute('required', 'required');
            noteAuto.style.display = 'block';
            
            // Trigger calculation
            calculateDendaTelat(id);
            
            // Set required for hari telat
            document.getElementById('hari_telat_' + id).setAttribute('required', 'required');
            
        } else if (jenis === 'masalah_unit') {
            divKet.style.display = 'block';
            divTotal.style.display = 'block';
            inputTotal.setAttribute('required', 'required');
            inputTotal.value = ''; // Reset value to allow manual entry
            
            document.getElementById('hari_telat_' + id).removeAttribute('required');
        }
    }

    function calculateDendaTelat(id) {
        var hari = document.getElementById('hari_telat_' + id).value;
        var totalInput = document.getElementById('total_denda_' + id);
        
        if (hari && hari > 0) {
            var total = hari * 100000;
            totalInput.value = total;
        } else {
            totalInput.value = 0;
        }
    }
</script>
@endsection
