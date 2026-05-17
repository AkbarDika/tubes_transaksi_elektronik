<?php

namespace App\Http\Controllers;

use App\Models\Kontrak;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KontrakController extends Controller
{
    // ─────────────────────────────────────────
    //  ADMIN: Daftar semua kontrak
    // ─────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Kontrak::with(['pemesanan.user', 'pemesanan.details.mobil.kategori'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nomor_kontrak', 'like', '%' . $request->search . '%')
                ->orWhereHas('pemesanan.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        $kontrak = $query->paginate(10)->withQueryString();

        return view('admin.kontrak.index', compact('kontrak'));
    }

    // ─────────────────────────────────────────
    //  ADMIN: Detail satu kontrak
    // ─────────────────────────────────────────
    public function show($id)
    {
        $kontrak = Kontrak::with(['pemesanan.user', 'pemesanan.details.mobil.kategori', 'pemesanan.pembayaran'])
            ->findOrFail($id);

        return view('admin.kontrak.show', compact('kontrak'));
    }

    // ─────────────────────────────────────────
    //  ADMIN: Generate kontrak dari pemesanan
    // ─────────────────────────────────────────
    public function generate($pemesananId)
    {
        $pemesanan = Pemesanan::with(['user', 'details.mobil', 'pembayaran'])
            ->findOrFail($pemesananId);

        // Cek apakah kontrak sudah ada untuk pemesanan ini
        $existing = Kontrak::where('pemesanan_id', $pemesananId)->first();
        if ($existing) {
            return redirect()->route('admin.kontrak.show', $existing->id)
                ->with('info', 'Kontrak untuk pemesanan ini sudah ada: ' . $existing->nomor_kontrak);
        }

        // Hanya generate kontrak jika pemesanan sudah disetujui
        if (!in_array($pemesanan->status, ['disetujui', 'selesai'])) {
            return back()->with('error', 'Kontrak hanya bisa dibuat untuk pemesanan dengan status "Disetujui" atau "Selesai".');
        }

        $kontrak = Kontrak::create([
            'pemesanan_id'    => $pemesanan->id,
            'nomor_kontrak'   => Kontrak::generateNomor(),
            'tanggal_kontrak' => now()->toDateString(),
            'tanggal_mulai'   => $pemesanan->tanggal_mulai,
            'tanggal_selesai' => $pemesanan->tanggal_selesai,
            'total_harga'     => $pemesanan->total_harga,
            'status'          => $pemesanan->status === 'selesai' ? 'selesai' : 'aktif',
            'catatan'         => null,
        ]);

        return redirect()->route('admin.kontrak.show', $kontrak->id)
            ->with('success', 'Kontrak berhasil dibuat: ' . $kontrak->nomor_kontrak);
    }

    // ─────────────────────────────────────────
    //  ADMIN: Update status kontrak
    // ─────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:aktif,selesai,dibatalkan',
            'catatan' => 'nullable|string|max:500',
        ]);

        $kontrak = Kontrak::findOrFail($id);
        $kontrak->update([
            'status'  => $request->status,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Status kontrak berhasil diperbarui.');
    }

    // ─────────────────────────────────────────
    //  ADMIN: Hapus kontrak
    // ─────────────────────────────────────────
    public function destroy($id)
    {
        $kontrak = Kontrak::findOrFail($id);
        $kontrak->delete();

        return redirect()->route('admin.kontrak.index')
            ->with('success', 'Kontrak berhasil dihapus.');
    }

    // ─────────────────────────────────────────
    //  ADMIN & USER: Download PDF kontrak
    // ─────────────────────────────────────────
    public function download($id)
    {
        $kontrak = Kontrak::with(['pemesanan.user', 'pemesanan.details.mobil.kategori', 'pemesanan.pembayaran'])
            ->findOrFail($id);

        // Jika customer, pastikan hanya bisa download miliknya sendiri
        if (auth()->user()->role_id !== 1 && auth()->user()->role_id !== 4) {
            if ($kontrak->pemesanan->user_id !== auth()->id()) {
                abort(403, 'Anda tidak berhak mengakses kontrak ini.');
            }
        }

        $pdf = Pdf::loadView('kontrak.template', compact('kontrak'))
            ->setPaper('A4', 'portrait');

        $filename = 'Kontrak-' . $kontrak->nomor_kontrak . '.pdf';

        return $pdf->download($filename);
    }

    // ─────────────────────────────────────────
    //  USER: Lihat daftar kontrak milik sendiri
    // ─────────────────────────────────────────
    public function myKontrak()
    {
        $kontrak = Kontrak::with(['pemesanan.details.mobil.kategori'])
            ->whereHas('pemesanan', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.kontrak.index', compact('kontrak'));
    }
}
