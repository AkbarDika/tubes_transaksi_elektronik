<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KategoriMobilController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestCallbackController;
use App\Http\Controllers\RiwayatPesananController;
use App\Models\Car;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role_id) {
            1 => redirect()->route('admin.dashboard'),
            4 => redirect()->route('petugas.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }

    return view('landing', [
        'cars' => Car::where('status', 'tersedia')->latest()->take(4)->get()
    ]);
})->name('landing');


/*
|--------------------------------------------------------------------------
| Midtrans Callback (Webhook - No Authentication/CSRF Needed)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Auth Routes (Custom)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'formRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);







Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Catalog Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/catalog/search', [CatalogController::class, 'search'])->name('catalog.search');
Route::get('/catalog/category/{kategoriId}', [CatalogController::class, 'filterByCategory'])->name('catalog.filter-category');
Route::get('/catalog/filter-price', [CatalogController::class, 'filterByPrice'])->name('catalog.filter-price');

/*
|--------------------------------------------------------------------------
| Protected Routes - Customer
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Rental Routes
    Route::get('/rental/{carId}', [RentalController::class, 'create'])->name('rental.create');
    Route::post('/rental', [RentalController::class, 'store'])->name('rental.store');
    Route::get('/rental/{pemesananId}/detail', [RentalController::class, 'show'])->name('rental.show');
    Route::get('/rental/{pemesananId}/edit', [RentalController::class, 'edit'])->name('rental.edit');
    Route::post('/rental/{pemesananId}', [RentalController::class, 'update'])->name('rental.update');
    Route::post('/rental/{pemesananId}/cancel', [RentalController::class, 'cancel'])->name('rental.cancel');

    // Payment Routes
    Route::get('/pemesanan/{pemesanan}/payment', [PemesananController::class, 'payment'])->name('pemesanan.payment');
    Route::get('/pemesanan/{pemesanan}/success', [PemesananController::class, 'paymentSuccess'])->name('pemesanan.success');
    Route::get('/pemesanan/{pemesanan}/failed', [PemesananController::class, 'paymentFailed'])->name('pemesanan.failed');

    // Test Callback Routes (Development Only - Hapus di Production!)
    Route::get('/test/midtrans-callback', [TestCallbackController::class, 'index'])->name('test.midtrans-callback-form');
    Route::post('/test/midtrans-callback', [TestCallbackController::class, 'simulateCallback'])->name('test.midtrans-callback');
    Route::get('/user/riwayat-pesanan', [RiwayatPesananController::class, 'index'])->name('user.riwayat_pesanan');


    Route::post('/user/pengembalian', [PengembalianController::class, 'storeUser'])
        ->name('pengembalian.storeUser');

});

/*
|--------------------------------------------------------------------------
| Protected Routes - Petugas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'petugas'])->group(function () {
    
    // Petugas Dashboard
    Route::get('/petugas', [App\Http\Controllers\PetugasController::class, 'index'])->name('petugas.dashboard');
    
    // Petugas - Kelola Pemesanan
    Route::get('/petugas/pemesanan', [App\Http\Controllers\PetugasController::class, 'pemesanan'])->name('petugas.pemesanan');
    Route::put('/petugas/pemesanan/{id}/konfirmasi', [App\Http\Controllers\PetugasController::class, 'konfirmasiPemesanan'])->name('petugas.pemesanan.konfirmasi');
    
    // Petugas - Kelola Pengembalian
    Route::get('/petugas/pengembalian', [App\Http\Controllers\PetugasController::class, 'pengembalian'])->name('petugas.pengembalian');
    Route::put('/petugas/pengembalian/{id}/konfirmasi', [App\Http\Controllers\PetugasController::class, 'konfirmasiPengembalian'])->name('petugas.pengembalian.konfirmasi');

});

/*
|--------------------------------------------------------------------------
| Protected Routes - Admin
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'admin'])->group(function () {
    
    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard.alt');
    
    // Admin - Kelola Mobil
    Route::get('/mobil', [MobilController::class, 'index'])->name('mobil.index');
    Route::post('/mobil', [MobilController::class, 'store'])->name('mobil.store');
    Route::put('/mobil/{mobil}', [MobilController::class, 'update'])->name('mobil.update');
    Route::delete('/mobil/{mobil}', [MobilController::class, 'destroy'])->name('mobil.destroy');


    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::post('/pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{pemesanan}', [PemesananController::class, 'show'])->name('pemesanan.show');
    Route::put('/pemesanan/{pemesanan}', [PemesananController::class, 'update'])->name('pemesanan.update');
    Route::delete('/pemesanan/{pemesanan}', [PemesananController::class, 'destroy'])->name('pemesanan.destroy');

    Route::get('/kategori', [KategoriMobilController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriMobilController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [KategoriMobilController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriMobilController::class, 'destroy'])->name('kategori.destroy');
    
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::put('/pengembalian/{id}', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::delete('/pengembalian/{id}', [PengembalianController::class, 'destroy'])->name('pengembalian.destroy');

    Route::get('/user', [UserController::class, 'index'])
        ->name('user.index');

    Route::post('/user', [UserController::class, 'store'])
        ->name('user.store');

    Route::put('/user/{id}', [UserController::class, 'update'])
        ->name('user.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->name('user.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/export-pesanan', [LaporanController::class, 'exportPesanan'])->name('admin.laporan.export-pesanan');
    Route::get('/laporan/export-pembayaran', [LaporanController::class, 'exportPembayaran'])->name('admin.laporan.export-pembayaran');
    Route::get('/laporan/export-pengembalian', [LaporanController::class, 'exportPengembalian'])->name('admin.laporan.export-pengembalian');
});