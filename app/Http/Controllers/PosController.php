<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCashPaymentRequest;
use App\Models\Pemesanan;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PosController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Daftar pemesanan siap dibayar tunai di kasir (POS).
     */
    public function index(Request $request)
    {
        $query = Pemesanan::with(['user', 'details.mobil', 'pembayaran'])
            ->readyForPayment()
            ->whereHas('pembayaran', fn ($q) => $q->where('metode_pembayaran', 'Tunai'))
            ->orderByDesc('updated_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $pemesanan = $query->paginate(15)->withQueryString();

        return view('petugas.pos.index', compact('pemesanan'));
    }

    /**
     * Form POS / validasi uang tunai untuk satu pemesanan.
     */
    public function show(Pemesanan $pemesanan)
    {
        if (!$pemesanan->canAcceptPayment()) {
            return redirect()
                ->route('petugas.pos.index')
                ->with('error', 'Pemesanan ini tidak dapat diproses di POS (belum disetujui atau sudah lunas).');
        }

        $pemesanan->load(['user', 'details.mobil']);

        return view('petugas.pos.show', compact('pemesanan'));
    }

    /**
     * Proses pembayaran tunai oleh petugas (memproses pengecekan dan upload gambar).
     */
    public function processCash(Request $request, Pemesanan $pemesanan)
    {
        $action = $request->input('action', 'setujui');

        if ($action === 'tolak') {
            $buktiPath = null;
            if ($request->hasFile('bukti_bayar')) {
                $request->validate([
                    'bukti_bayar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $file = $request->file('bukti_bayar');
                $filename = 'cash_reject_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('pembayaran', $filename, 'public');
            }

            $pembayaran = $this->paymentService->processCashPayment(
                $pemesanan,
                0.0,
                auth()->id(),
                'ditolak',
                $buktiPath
            );

            return redirect()
                ->route('petugas.pos.index')
                ->with('warning', sprintf('Pemesanan #%d berhasil ditolak untuk pembayaran tunai.', $pemesanan->id));
        }

        // Aksi Setujui: Validasi standard & upload gambar
        $request->validate([
            'uang_diterima' => 'required|numeric|min:0',
            'bukti_bayar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $total = round((float) $pemesanan->total_harga, 2);
        $uang = round((float) $request->uang_diterima, 2);

        if ($uang < $total) {
            $kurang = $total - $uang;
            return back()
                ->withErrors(['uang_diterima' => 'Uang tunai tidak mencukupi. Kurang Rp ' . number_format($kurang, 0, ',', '.')])
                ->withInput();
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = 'cash_success_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('pembayaran', $filename, 'public');
        }

        try {
            $pembayaran = $this->paymentService->processCashPayment(
                $pemesanan,
                $uang,
                auth()->id(),
                'valid',
                $buktiPath
            );

            return redirect()
                ->route('petugas.pos.index')
                ->with('success', sprintf(
                    'Pembayaran tunai pemesanan #%d berhasil disetujui. Kembalian: Rp %s',
                    $pemesanan->id,
                    number_format($pembayaran->kembalian, 0, ',', '.')
                ));
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
