@php
    $totalTagihan = (float) $pemesanan->total_harga;
    $formAction = $formAction ?? route('pemesanan.payment.cash', $pemesanan);
    $submitLabel = $submitLabel ?? 'Konfirmasi Bayar Tunai';
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="cash-payment-form" id="cashPaymentForm">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Total Tagihan</label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="text" class="form-control fw-bold bg-light" readonly
                   value="{{ number_format($totalTagihan, 0, ',', '.') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="uang_diterima" class="form-label fw-semibold">Uang Tunai Diterima <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number"
                   name="uang_diterima"
                   id="uang_diterima"
                   class="form-control @error('uang_diterima') is-invalid @enderror"
                   min="0"
                   step="1000"
                   required
                   placeholder="0"
                   value="{{ old('uang_diterima') }}">
        </div>
        @error('uang_diterima')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="text-muted">Uang diterima harus &ge; total tagihan.</small>
    </div>

    <div class="mb-3">
        <label for="bukti_bayar" class="form-label fw-semibold">Unggah Foto Uang / Bukti Fisik <span class="text-danger">*</span></label>
        <input type="file"
               name="bukti_bayar"
               id="bukti_bayar"
               class="form-control @error('bukti_bayar') is-invalid @enderror"
               accept="image/*"
               required>
        @error('bukti_bayar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="text-muted">Upload foto uang kertas/koin untuk arsip verifikasi fisik.</small>
    </div>

    <div class="alert alert-secondary mb-3" id="kembalianBox">
        <div class="d-flex justify-content-between align-items-center">
            <span>Kembalian</span>
            <strong class="text-success fs-5" id="kembalianDisplay">Rp 0</strong>
        </div>
    </div>

    <button type="submit" name="action" value="setujui" class="btn btn-success btn-lg w-100 fw-bold" id="btnBayarTunai" disabled>
        <i class="bi bi-check-circle-fill me-1"></i> {{ $submitLabel }}
    </button>
    
    <button type="submit" name="action" value="tolak" class="btn btn-danger btn-md w-100 fw-bold mt-2" onclick="disableRequiredFields();">
        <i class="bi bi-x-circle-fill me-1"></i> Tolak Pembayaran
    </button>
</form>

<script>
function disableRequiredFields() {
    document.getElementById('uang_diterima').removeAttribute('required');
    document.getElementById('bukti_bayar').removeAttribute('required');
    if(confirm('Apakah Anda yakin ingin menolak transaksi tunai ini?')) {
        document.getElementById('cashPaymentForm').submit();
    }
}

(function () {
    const total = {{ $totalTagihan }};
    const input = document.getElementById('uang_diterima');
    const kembalianEl = document.getElementById('kembalianDisplay');
    const btn = document.getElementById('btnBayarTunai');
    const box = document.getElementById('kembalianBox');

    if (!input) return;

    function formatRp(n) {
        return 'Rp ' + Math.max(0, n).toLocaleString('id-ID');
    }

    function update() {
        const uang = parseFloat(input.value) || 0;
        const kembalian = uang - total;
        kembalianEl.textContent = formatRp(kembalian);

        if (uang >= total && total > 0) {
            btn.disabled = false;
            box.classList.remove('alert-danger');
            box.classList.add('alert-secondary');
        } else {
            btn.disabled = true;
            if (uang > 0 && uang < total) {
                box.classList.remove('alert-secondary');
                box.classList.add('alert-danger');
                kembalianEl.textContent = 'Kurang ' + formatRp(total - uang);
            }
        }
    }

    input.addEventListener('input', update);
    update();
})();
</script>
