<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrak Sewa Kendaraan - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.6;
        }

        /* HEADER */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .header-logo {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }
        .header-logo h1 {
            font-size: 22px;
            color: #2563eb;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header-logo p {
            color: #555;
            font-size: 11px;
            margin-top: 2px;
        }
        .header-nomor {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }
        .header-nomor .badge-kontrak {
            background: #2563eb;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        /* JUDUL */
        .judul {
            text-align: center;
            margin: 18px 0 16px;
        }
        .judul h2 {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1e3a8a;
            border-bottom: 1px dashed #93c5fd;
            display: inline-block;
            padding-bottom: 4px;
        }
        .judul .sub {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
        }

        /* INFO BOXES */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .info-box {
            display: table-cell;
            width: 48%;
            background: #f0f6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 10px 14px;
            vertical-align: top;
        }
        .info-box + .info-box {
            margin-left: 4%;
        }
        .info-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #1d4ed8;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 4px;
        }
        .info-table {
            width: 100%;
        }
        .info-table tr td {
            padding: 2px 0;
        }
        .info-table tr td:first-child {
            color: #555;
            width: 45%;
        }
        .info-table tr td:last-child {
            font-weight: bold;
        }

        /* DETAIL SEWA */
        .section {
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1d4ed8;
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 6px 10px;
            margin-bottom: 8px;
        }

        /* TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .data-table thead tr {
            background: #1d4ed8;
            color: white;
        }
        .data-table thead th {
            padding: 8px 10px;
            text-align: left;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .data-table tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tfoot tr {
            background: #eff6ff;
        }
        .data-table tfoot td {
            padding: 8px 10px;
            font-weight: bold;
            border-top: 2px solid #2563eb;
        }

        /* SYARAT */
        .syarat-list {
            padding-left: 0;
            list-style: none;
        }
        .syarat-list li {
            padding: 3px 0 3px 16px;
            position: relative;
            font-size: 11px;
            color: #374151;
        }
        .syarat-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #2563eb;
            font-weight: bold;
        }

        /* TANDA TANGAN */
        .ttd-row {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .ttd-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: bottom;
        }
        .ttd-box .ttd-line {
            border-top: 1px solid #1a1a1a;
            margin-top: 50px;
            padding-top: 6px;
        }
        .ttd-box p {
            font-size: 11px;
        }
        .ttd-box .ttd-label {
            font-weight: bold;
            color: #1d4ed8;
            font-size: 11px;
            margin-bottom: 2px;
        }

        /* FOOTER */
        .doc-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        /* STATUS BADGE */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-aktif      { background: #dbeafe; color: #1d4ed8; }
        .status-selesai    { background: #dcfce7; color: #166534; }
        .status-dibatalkan { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-top">
            <div class="header-logo">
                <h1>🚗 RentCar Premium</h1>
                <p>Jl. Raya No. 123, Kota &nbsp;|&nbsp; Telp: (021) 123-4567 &nbsp;|&nbsp; rentalcarpremium@email.com</p>
            </div>
            <div class="header-nomor">
                <div class="badge-kontrak">{{ $kontrak->nomor_kontrak }}</div>
                <p style="color:#555; font-size:10px; margin-top:4px;">Tgl: {{ $kontrak->tanggal_kontrak->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- JUDUL -->
    <div class="judul">
        <h2>Kontrak Sewa Kendaraan</h2>
        <div class="sub">
            Status:
            <span class="status-badge status-{{ $kontrak->status }}">
                {{ strtoupper($kontrak->status) }}
            </span>
        </div>
    </div>

    <!-- INFO PIHAK -->
    <div class="info-row">
        <div class="info-box">
            <h3>🏢 Pihak Pertama (Pemberi Sewa)</h3>
            <table class="info-table">
                <tr><td>Nama Perusahaan</td><td>RentCar Premium</td></tr>
                <tr><td>Alamat</td><td>Jl. Raya No. 123</td></tr>
                <tr><td>Telepon</td><td>(021) 123-4567</td></tr>
                <tr><td>Email</td><td>rentalcarpremium@email.com</td></tr>
            </table>
        </div>
        <div class="info-box">
            <h3>👤 Pihak Kedua (Penyewa)</h3>
            <table class="info-table">
                <tr><td>Nama</td><td>{{ $kontrak->pemesanan->user->name }}</td></tr>
                <tr><td>Email</td><td>{{ $kontrak->pemesanan->user->email }}</td></tr>
                <tr><td>Telepon</td><td>{{ $kontrak->pemesanan->user->phone ?? '-' }}</td></tr>
                <tr><td>ID Pemesanan</td><td>#{{ $kontrak->pemesanan->id }}</td></tr>
            </table>
        </div>
    </div>

    <!-- DETAIL KENDARAAN -->
    <div class="section">
        <div class="section-title">📋 Detail Kendaraan yang Disewa</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kendaraan</th>
                    <th>Kategori</th>
                    <th>Plat Nomor</th>
                    <th>Lama Sewa (hari)</th>
                    <th>Harga/Hari</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kontrak->pemesanan->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->mobil->merk ?? '-' }}</td>
                    <td>{{ $detail->mobil->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $detail->mobil->nomor_plat ?? '-' }}</td>
                    <td style="text-align:center">{{ $detail->lama_sewa }}</td>
                    <td>Rp {{ number_format($detail->mobil->harga_sewa ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right">Total Biaya Sewa:</td>
                    <td>Rp {{ number_format($kontrak->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- DETAIL WAKTU SEWA -->
    <div class="info-row">
        <div class="info-box">
            <h3>📅 Periode Sewa</h3>
            <table class="info-table">
                <tr><td>Tanggal Mulai</td><td>{{ $kontrak->tanggal_mulai->format('d F Y') }}</td></tr>
                <tr><td>Tanggal Selesai</td><td>{{ $kontrak->tanggal_selesai->format('d F Y') }}</td></tr>
                <tr><td>Durasi Sewa</td><td>{{ $kontrak->durasi_sewa }} hari</td></tr>
                <tr><td>Total Biaya</td><td>Rp {{ number_format($kontrak->total_harga, 0, ',', '.') }}</td></tr>
            </table>
        </div>
        <div class="info-box">
            <h3>💳 Informasi Pembayaran</h3>
            <table class="info-table">
                @if($kontrak->pemesanan->pembayaran)
                <tr><td>Metode</td><td>{{ $kontrak->pemesanan->pembayaran->metode_pembayaran }}</td></tr>
                <tr><td>Tanggal Bayar</td><td>{{ \Carbon\Carbon::parse($kontrak->pemesanan->pembayaran->tanggal_bayar)->format('d/m/Y') }}</td></tr>
                <tr><td>Jumlah Bayar</td><td>Rp {{ number_format($kontrak->pemesanan->pembayaran->jumlah_bayar, 0, ',', '.') }}</td></tr>
                <tr><td>Status</td><td>{{ strtoupper($kontrak->pemesanan->pembayaran->status) }}</td></tr>
                @else
                <tr><td colspan="2" style="color:#ef4444">Belum Ada Pembayaran</td></tr>
                @endif
            </table>
        </div>
    </div>

    <!-- SYARAT & KETENTUAN -->
    <div class="section">
        <div class="section-title">📜 Syarat & Ketentuan Sewa Kendaraan</div>
        <ul class="syarat-list">
            <li>Penyewa wajib merawat kendaraan dengan baik selama masa sewa berlangsung.</li>
            <li>Kendaraan harus dikembalikan dalam kondisi yang sama seperti saat diterima (bersih dan tidak rusak).</li>
            <li>Keterlambatan pengembalian kendaraan akan dikenakan denda sebesar Rp 100.000/hari.</li>
            <li>Kerusakan yang ditimbulkan selama masa sewa menjadi tanggung jawab penyewa sepenuhnya.</li>
            <li>Penyewa dilarang menggunakan kendaraan untuk tindakan yang melanggar hukum.</li>
            <li>Penyewa dilarang menyewakan kembali kendaraan kepada pihak lain tanpa izin tertulis dari pihak pertama.</li>
            <li>Bahan bakar ditanggung sepenuhnya oleh penyewa (kendaraan diterima dan dikembalikan dalam kondisi tangki penuh).</li>
            <li>Pihak pertama berhak membatalkan kontrak ini apabila terjadi pelanggaran terhadap ketentuan di atas.</li>
        </ul>
    </div>

    @if($kontrak->catatan)
    <div class="section">
        <div class="section-title">📝 Catatan Tambahan</div>
        <p style="padding: 8px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px; font-size: 11px;">
            {{ $kontrak->catatan }}
        </p>
    </div>
    @endif

    <!-- PERNYATAAN -->
    <div class="section" style="background:#f0fdf4; border:1px solid #86efac; border-radius:6px; padding:10px 14px;">
        <p style="font-size:11px; color:#166534;">
            Dengan ditandatanganinya kontrak ini, kedua belah pihak menyatakan telah membaca, memahami, dan
            menyetujui seluruh ketentuan yang tercantum dalam kontrak sewa kendaraan ini dengan sadar dan
            tanpa paksaan dari pihak manapun.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd-row">
        <div class="ttd-box">
            <div class="ttd-line">
                <div class="ttd-label">Pihak Pertama</div>
                <p>RentCar Premium</p>
            </div>
        </div>
        <div class="ttd-box">
            <div class="ttd-line" style="border-color:#e2e8f0; color:#9ca3af;">
                <div class="ttd-label" style="color:#9ca3af;">Saksi</div>
                <p style="color:#9ca3af;">__________________</p>
            </div>
        </div>
        <div class="ttd-box">
            <div class="ttd-line">
                <div class="ttd-label">Pihak Kedua</div>
                <p>{{ $kontrak->pemesanan->user->name }}</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="doc-footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem RentCar Premium pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p>Nomor Kontrak: <strong>{{ $kontrak->nomor_kontrak }}</strong> &nbsp;|&nbsp; ID Pemesanan: <strong>#{{ $kontrak->pemesanan->id }}</strong></p>
    </div>

</body>
</html>
