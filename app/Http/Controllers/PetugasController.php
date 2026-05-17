<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pengembalian;
use App\Models\Denda;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    /**
     * Dashboard petugas
     */
    public function index()
    {
        // Statistik untuk dashboard
        $totalPemesananPending = Pemesanan::where('status', 'pending')->count();
        $totalPemesananDisetujuiHariIni = Pemesanan::where('status', 'disetujui')
            ->whereDate('updated_at', today())
            ->count();
        
        $totalPengembalianPending = Pengembalian::where('status_pengembalian', 'pending')->count();
        $totalPengembalianSelesaiHariIni = Pengembalian::where('status_pengembalian', 'selesai')
            ->whereDate('updated_at', today())
            ->count();

        // Data terbaru
        $pemesananTerbaru = Pemesanan::with(['user', 'details.mobil', 'pembayaran', 'pengembalian'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pengembalianTerbaru = Pengembalian::with(['pemesanan.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('petugas.index', compact(
            'totalPemesananPending',
            'totalPemesananDisetujuiHariIni',
            'totalPengembalianPending',
            'totalPengembalianSelesaiHariIni',
            'pemesananTerbaru',
            'pengembalianTerbaru'
        ));
    }

    /**
     * Halaman daftar pemesanan
     */
    public function pemesanan()
    {
        $pemesanan = Pemesanan::with(['user', 'details.mobil', 'pembayaran', 'pengembalian'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('petugas.pemesanan.index', compact('pemesanan'));
    }

    /**
     * Konfirmasi/update status pemesanan
     */
    public function konfirmasiPemesanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,selesai'
        ]);

        $pemesanan = Pemesanan::with('details.mobil')->findOrFail($id);
        
        DB::transaction(function () use ($pemesanan, $request) {
            $pemesanan->update([
                'status' => $request->status
            ]);

            // Jika status disetujui, ubah status mobil jadi disewa
            if ($request->status == 'disetujui') {
                foreach ($pemesanan->details as $detail) {
                    if ($detail->mobil) {
                        $detail->mobil->update(['status' => 'disewa']);
                    }
                }
            }
            // Optional: Jika status ditolak/selesai, ubah status mobil jadi tersedia (jika logic bisnis membutuhkan)
             elseif ($request->status == 'ditolak' || $request->status == 'selesai') {
                foreach ($pemesanan->details as $detail) {
                    if ($detail->mobil) {
                        $detail->mobil->update(['status' => 'tersedia']);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Status pemesanan berhasil diupdate');
    }

    /**
     * Halaman daftar pengembalian
     */
    public function pengembalian()
    {
        $pengembalian = Pengembalian::with(['pemesanan.user', 'pemesanan.details.mobil'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('petugas.pengembalian.index', compact('pengembalian'));
    }

    /**
     * Konfirmasi/update status pengembalian
     */
    public function konfirmasiPengembalian(Request $request, $id)
    {
        $request->validate([
            'status_pengembalian' => 'required|in:pending,selesai,bermasalah',
            'jenis_denda' => 'required_if:status_pengembalian,bermasalah|nullable|in:telat,masalah_unit',
            'hari_telat' => 'required_if:jenis_denda,telat|nullable|integer|min:1',
            'keterangan_denda' => 'required_if:jenis_denda,masalah_unit|nullable|string|max:150',
            'total_denda' => 'required_if:jenis_denda,telat|required_if:jenis_denda,masalah_unit|nullable|numeric|min:0',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        DB::transaction(function () use ($pengembalian, $request) {

            $pengembalian->update([
                'status_pengembalian' => $request->status_pengembalian
            ]);

            if ($request->status_pengembalian === 'bermasalah') {

                Denda::where('pengembalian_id', $pengembalian->id_pengembalian)->delete();

                $data = [
                    'pengembalian_id' => $pengembalian->id_pengembalian,
                    'jenis_denda' => $request->jenis_denda,
                ];

                if ($request->jenis_denda === 'telat') {
                    $data['jumlah_hari_terlambat'] = $request->hari_telat;
                    $data['tarif_denda_per_hari'] = 100000;
                    $data['total_denda'] = $request->hari_telat * 100000;
                }

                if ($request->jenis_denda === 'masalah_unit') {
                    $data['keterangan'] = $request->keterangan_denda ?? '-';
                    $data['total_denda'] = $request->total_denda ?? 0;
                }

                Denda::create($data);
            }
        });

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi');
    }

}
