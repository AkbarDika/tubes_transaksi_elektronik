<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use FPDF;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function exportPesanan()
    {
        $data = Pemesanan::with(['user', 'details.mobil', 'pembayaran', 'pengembalian'])->latest()->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN DATA PEMESANAN RENTAL MOBIL', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(30, 10, 'ID Pesanan', 1, 0, 'C');
        $pdf->Cell(45, 10, 'Pelanggan', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Mobil', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Tgl Sewa', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Total Harga', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Status', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        foreach ($data as $index => $p) {
            $pdf->Cell(10, 10, $index + 1, 1, 0, 'C');
            $pdf->Cell(30, 10, '#PES' . str_pad($p->id, 3, '0', STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(45, 10, $p->user->name, 1, 0, 'L');
            
            $mobilNames = [];
            foreach($p->details as $detail) {
                $mobilNames[] = $detail->mobil->merk . ' ' . $detail->mobil->model;
            }
            $pdf->Cell(60, 10, implode(', ', $mobilNames), 1, 0, 'L');
            
            $pdf->Cell(35, 10, $p->tanggal_mulai->format('d/m/Y'), 1, 0, 'C');
            $pdf->Cell(35, 10, 'Rp ' . number_format($p->total_harga, 0, ',', '.'), 1, 0, 'R');
            $pdf->Cell(60, 10, $p->status_tampilan, 1, 1, 'C');
        }

        $pdf->Output('D', 'Laporan_Pemesanan_' . date('Y-m-d') . '.pdf');
        exit;
    }

    public function exportPembayaran()
    {
        $data = Pembayaran::with(['pemesanan.user'])->latest()->get();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN DATA PEMBAYARAN RENTAL MOBIL', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(35, 10, 'ID Pesanan', 1, 0, 'C');
        $pdf->Cell(45, 10, 'Pelanggan', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Tgl Bayar', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Jumlah Bayar', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Metode', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        foreach ($data as $index => $p) {
            $pdf->Cell(10, 10, $index + 1, 1, 0, 'C');
            $pdf->Cell(35, 10, '#PES' . str_pad($p->pemesanan_id, 3, '0', STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(45, 10, $p->pemesanan->user->name, 1, 0, 'L');
            $pdf->Cell(35, 10, date('d/m/Y', strtotime($p->tanggal_bayar)), 1, 0, 'C');
            $pdf->Cell(35, 10, 'Rp ' . number_format($p->jumlah_bayar, 0, ',', '.'), 1, 0, 'R');
            $pdf->Cell(30, 10, $p->metode_pembayaran, 1, 1, 'C');
        }

        $pdf->Output('D', 'Laporan_Pembayaran_' . date('Y-m-d') . '.pdf');
        exit;
    }

    public function exportPengembalian()
    {
        $data = Pengembalian::with(['pemesanan.user'])->latest()->get();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'LAPORAN DATA PENGEMBALIAN RENTAL MOBIL', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No', 1, 0, 'C');
        $pdf->Cell(35, 10, 'ID Pesanan', 1, 0, 'C');
        $pdf->Cell(45, 10, 'Pelanggan', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Tgl Kembali', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Kondisi', 1, 0, 'C');
        $pdf->Cell(110, 10, 'Catatan', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        foreach ($data as $index => $p) {
            $pdf->Cell(10, 10, $index + 1, 1, 0, 'C');
            $pdf->Cell(35, 10, '#PES' . str_pad($p->pemesanan_id, 3, '0', STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(45, 10, $p->pemesanan->user->name, 1, 0, 'L');
            $pdf->Cell(35, 10, date('d/m/Y', strtotime($p->tanggal_kembali)), 1, 0, 'C');
            $pdf->Cell(40, 10, $p->kondisi_mobil, 1, 0, 'C');
            $pdf->Cell(110, 10, $p->catatan ?: '-', 1, 1, 'L');
        }

        $pdf->Output('D', 'Laporan_Pengembalian_' . date('Y-m-d') . '.pdf');
        exit;
    }
}
