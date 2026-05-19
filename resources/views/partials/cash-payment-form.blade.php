@php
    $totalTagihan = (float) $pemesanan->total_harga;
    $formAction = $formAction ?? route('pemesanan.payment.cash', $pemesanan);
    $submitLabel = $submitLabel ?? 'Konfirmasi Bayar Tunai';
@endphp

<form action="{{ $formAction }}" method="POST" class="cash-payment-form">
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

    <div class="alert alert-secondary mb-3" id="kembalianBox">
        <div class="d-flex justify-content-between align-items-center">
            <span>Kembalian</span>
            <strong class="text-success fs-5" id="kembalianDisplay">Rp 0</strong>
        </div>
    </div>

    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold" id="btnBayarTunai" disabled>
        <i class="bi bi-cash-coin"></i> {{ $submitLabel }}
    </button>
</form>

<script>
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
