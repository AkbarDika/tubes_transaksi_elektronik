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
     * Proses pembayaran tunai oleh petugas (secure: total dari DB).
     */
    public function processCash(ProcessCashPaymentRequest $request, Pemesanan $pemesanan)
    {
        try {
            $pembayaran = $this->paymentService->processCashPayment(
                $pemesanan,
                (float) $request->uang_diterima,
                auth()->id()
            );

            return redirect()
                ->route('petugas.pos.index')
                ->with('success', sprintf(
                    'Pembayaran tunai pemesanan #%d berhasil. Kembalian: Rp %s',
                    $pemesanan->id,
                    number_format($pembayaran->kembalian, 0, ',', '.')
                ));
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
