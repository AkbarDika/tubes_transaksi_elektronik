<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\DetailPemesanan;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin
     */
    public function index()
    {
        $totalMobil = Car::count();
        $pemesananAktif = Pemesanan::where('status', 'disetujui')->count();
        $totalPendapatan = Pembayaran::where('status', 'valid')->sum('jumlah_bayar');
        $totalPengguna = User::count();

        $pemesananTerbaru = Pemesanan::with(['user', 'details.mobil'])
            ->latest()
            ->take(8)
            ->get();

        // Data for Revenue Chart (Current Year)
        $currentYear = date('Y');
        $revenueData = Pembayaran::where('status', 'valid')
            ->whereYear('tanggal_bayar', $currentYear)
            ->select(
                DB::raw('MONTH(tanggal_bayar) as month'),
                DB::raw('SUM(jumlah_bayar) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = (float)($revenueData[$i] ?? 0);
        }

        // Data for Pie Chart (Car Popularity)
        $carPopularity = DetailPemesanan::join('mobil', 'detail_pemesanan.mobil_id', '=', 'mobil.id')
            ->select(
                DB::raw('CONCAT(mobil.merk, " ", mobil.model) as car_name'),
                DB::raw('COUNT(*) as total_rentals')
            )
            ->groupBy('car_name')
            ->orderBy('total_rentals', 'desc')
            ->take(5) // Top 5 cars
            ->get();

        $pieChartData = $carPopularity->map(function ($item) {
            return [
                'name' => $item->car_name,
                'y' => (int)$item->total_rentals
            ];
        });

        return view('admin.index', compact(
            'totalMobil',
            'pemesananAktif',
            'totalPendapatan',
            'totalPengguna',
            'pemesananTerbaru',
            'monthlyRevenue',
            'pieChartData'
        ));
    }

    /**
     * Halaman kelola mobil
     */
    public function cars()
    {
        // TODO: Tambahkan logika untuk menampilkan data mobil
        return view('admin.cars.index');
    }

    /**
     * Halaman kelola pemesanan
     */
    public function orders()
    {
        // TODO: Tambahkan logika untuk menampilkan data pemesanan
        return view('admin.orders.index');
    }

    /**
     * Halaman kelola pembayaran
     */
    public function payments()
    {
        // TODO: Tambahkan logika untuk menampilkan data pembayaran
        return view('admin.payments.index');
    }

    /**
     * Halaman kelola pengguna
     */
    public function users()
    {
        // TODO: Tambahkan logika untuk menampilkan data pengguna
        return view('admin.users.index');
    }
}
