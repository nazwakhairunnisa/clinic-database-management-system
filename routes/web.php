<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Owner\PasienController as OwnerPasienController;
use App\Http\Controllers\Admin\PasienController as AdminPasienController;
use App\Http\Controllers\Owner\TreatmentController as OwnerTreatmentController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Owner\StokObatController as OwnerStokObatController;
use App\Http\Controllers\Admin\StokObatController as AdminStokObatController;
use App\Http\Controllers\Owner\PembelianObatController as OwnerPembelianObatController;
use App\Http\Controllers\Admin\PembelianObatController as AdminPembelianObatController;
use App\Http\Controllers\Owner\JadwalReservasiController as OwnerJadwalReservasiController;
use App\Http\Controllers\Admin\JadwalReservasiController as AdminJadwalReservasiController;
use App\Http\Controllers\Owner\SupplierController as OwnerSupplierController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Owner\PengeluaranController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PromoController as OwnerPromoController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\JadwalOperasionalController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES (Guest - Belum Login)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/treatment', [HomeController::class, 'treatments'])->name('treatment');
Route::get('/treatment/all', [HomeController::class, 'treatmentsAll'])->name('treatment.all');
Route::get('/promo', [HomeController::class, 'promo'])->name('promo');
Route::get('/allpromo', [HomeController::class, 'allPromo'])->name('allpromo');
Route::get('/contact', [HomeController::class, 'contact'])->name('Contact');

// ============================================
// AUTH ROUTES (Login & Register)
// ============================================
require __DIR__.'/auth.php';

// ============================================
// OWNER & DOKTER ROUTES
// ============================================
Route::middleware(['auth', 'role:super admin,dokter'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk AJAX get jadwal
    Route::get('/dashboard/jadwal', [DashboardController::class, 'getJadwalByDate'])->name('dashboard.jadwal');
    Route::get('/dashboard/jadwal', [AdminController::class, 'getJadwalByDate'])->name('dashboard.jadwal');
    Route::get('/reservasi/get-jadwal', [App\Http\Controllers\ReservationController::class, 'getJadwalByDate'])
        ->name('reservasi.get-jadwal');
        

    // PASIEN ROUTES
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [OwnerPasienController::class, 'index'])->name('index');
        Route::get('/create', [OwnerPasienController::class, 'create'])->name('add');
        Route::post('/', [OwnerPasienController::class, 'store'])->name('store');
        Route::get('/export', [OwnerPasienController::class, 'export'])->name('export');
        Route::get('/{id}', [OwnerPasienController::class, 'show'])->name('show');
        Route::get('/{id}/edit-rekam', [OwnerPasienController::class, 'editRekamMedis'])->name('editRekam');
        Route::put('/{id}/rekam-medis', [OwnerPasienController::class, 'updateRekamMedis'])->name('updateRekam');
        Route::delete('/{id}', [OwnerPasienController::class, 'destroy'])->name('destroy');
    });

    // JADWAL RESERVASI ROUTES
    Route::prefix('jadwal-reservasi')->name('jadwal_reservasi.')->group(function () {
        Route::get('/', [OwnerJadwalReservasiController::class, 'index'])->name('index');
        Route::get('/export', [OwnerJadwalReservasiController::class, 'export'])->name('export');
        Route::get('/{id}', [OwnerJadwalReservasiController::class, 'show'])->name('show');
        Route::post('/{id}/status', [OwnerJadwalReservasiController::class, 'updateStatus'])->name('update-status');
    });

    // TREATMENT ROUTES
    Route::prefix('treatment')->name('treatment.')->group(function () {
        Route::get('/', [OwnerTreatmentController::class, 'index'])->name('index');
        Route::get('/create', [OwnerTreatmentController::class, 'create'])->name('add');
        Route::get('/export', [OwnerTreatmentController::class, 'export'])->name('export');
        Route::post('/', [OwnerTreatmentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [OwnerTreatmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OwnerTreatmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [OwnerTreatmentController::class, 'destroy'])->name('destroy');
    });

    // PROMO ROUTES
    Route::prefix('promo')->name('promo.')->group(function () {
        Route::get('/', [OwnerPromoController::class, 'index'])->name('index');
        Route::get('/create', [OwnerPromoController::class, 'create'])->name('add');
        Route::get('/export', [OwnerPromoController::class, 'export'])->name('export');
        Route::post('/', [OwnerPromoController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [OwnerPromoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OwnerPromoController::class, 'update'])->name('update');
        Route::delete('/{id}', [OwnerPromoController::class, 'destroy'])->name('destroy');
    });

    // STOK OBAT ROUTES
    Route::prefix('stok-obat')->name('stok-obat.')->group(function () {
        Route::get('/', [OwnerStokObatController::class, 'index'])->name('index');
        Route::get('/create', [OwnerStokObatController::class, 'create'])->name('add');
        Route::post('/', [OwnerStokObatController::class, 'store'])->name('store');
        Route::get('/stok-obat/export', [OwnerStokObatController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [OwnerStokObatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OwnerStokObatController::class, 'update'])->name('update');
        Route::delete('/{id}', [OwnerStokObatController::class, 'destroy'])->name('destroy');
    });

    // SUPPLIER ROUTES
    Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', [OwnerSupplierController::class, 'index'])->name('index');
        Route::get('/create', [OwnerSupplierController::class, 'create'])->name('add');
        Route::post('/', [OwnerSupplierController::class, 'store'])->name('store');
        Route::get('/supplier/export', [OwnerSupplierController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [OwnerSupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OwnerSupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [OwnerSupplierController::class, 'destroy'])->name('destroy');
    });

    // PEMBELIAN OBAT ROUTES
    Route::prefix('pembelian-obat')->name('pembelian_obat.')->group(function () {
        Route::get('/', [OwnerPembelianObatController::class, 'index'])->name('index');
        Route::get('/create', [OwnerPembelianObatController::class, 'create'])->name('add');
        Route::post('/', [OwnerPembelianObatController::class, 'store'])->name('store');
        Route::get('/pembelian-obat/export', [OwnerPembelianObatController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [OwnerPembelianObatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OwnerPembelianObatController::class, 'update'])->name('update');
        Route::delete('/{id}', [OwnerPembelianObatController::class, 'destroy'])->name('destroy');
    });

    // PENGELUARAN ROUTES (Khusus Owner & Dokter)
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [App\Http\Controllers\Owner\PengeluaranController::class, 'index'])->name('index');
        Route::get('/add', [App\Http\Controllers\Owner\PengeluaranController::class, 'create'])->name('add');
        Route::post('/', [App\Http\Controllers\Owner\PengeluaranController::class, 'store'])->name('store');
        Route::get('/export', [App\Http\Controllers\Owner\PengeluaranController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [App\Http\Controllers\Owner\PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Owner\PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Owner\PengeluaranController::class, 'destroy'])->name('destroy');
    });

    // PENDAPATAN ROUTES (Khusus Owner & Dokter)
    Route::prefix('pendapatan')->name('pendapatan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Owner\PendapatanController::class, 'index'])->name('index');
        Route::get('/add', [App\Http\Controllers\Owner\PendapatanController::class, 'create'])->name('add');
        Route::post('/', [App\Http\Controllers\Owner\PendapatanController::class, 'store'])->name('store');
        Route::get('/export', [App\Http\Controllers\Owner\PendapatanController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [App\Http\Controllers\Owner\PendapatanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Owner\PendapatanController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Owner\PendapatanController::class, 'destroy'])->name('destroy');
    });

    // LAPORAN PENJUALAN (Khusus Owner & Dokter)
    Route::prefix('laporan-penjualan')->name('laporan_penjualan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Owner\LaporanPenjualanController::class, 'index'])->name('index');
        Route::get('/export', [App\Http\Controllers\Owner\LaporanPenjualanController::class, 'export'])->name('export');
    });
});

// ============================================
// ADMIN (PETUGAS KLINIK) ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/dashboard/jadwal', [AdminController::class, 'getJadwalByDate'])->name('dashboard.jadwal');
    Route::get('/reservasi/get-jadwal', [App\Http\Controllers\ReservationController::class, 'getJadwalByDate'])
        ->name('reservasi.get-jadwal');
        
    Route::get('/reservasi/get-time-slots', [App\Http\Controllers\ReservationController::class, 'getAvailableTimeSlots'])
        ->name('reservasi.get-time-slots');
    
    // PASIEN ROUTES (sama seperti owner, tapi pakai prefix admin)
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [AdminPasienController::class, 'index'])->name('index');
        Route::get('/create', [AdminPasienController::class, 'create'])->name('add');
        Route::post('/', [AdminPasienController::class, 'store'])->name('store');
        Route::get('/export', [AdminPasienController::class, 'export'])->name('export');
        Route::get('/{id}', [AdminPasienController::class, 'show'])->name('show');
        Route::get('/{id}/edit-rekam', [AdminPasienController::class, 'editRekamMedis'])->name('editRekam');
        Route::put('/{id}/rekam-medis', [AdminPasienController::class, 'updateRekamMedis'])->name('updateRekam');
        Route::delete('/{id}', [AdminPasienController::class, 'destroy'])->name('destroy');
    });

    // Jadwal Operasional
    Route::get('/jadwal-operasional', [JadwalOperasionalController::class, 'index'])
        ->name('jadwal_operasional.index');
    Route::post('/jadwal-operasional', [JadwalOperasionalController::class, 'store'])
        ->name('jadwal_operasional.store');
    Route::put('/jadwal-operasional/{id}', [JadwalOperasionalController::class, 'update'])
        ->name('jadwal_operasional.update');
    Route::delete('/jadwal-operasional/{id}', [JadwalOperasionalController::class, 'destroy'])
        ->name('jadwal_operasional.destroy');
    Route::post('/jadwal-operasional/bulk', [JadwalOperasionalController::class, 'bulkStore'])
        ->name('jadwal_operasional.bulk-store');

    // JADWAL RESERVASI ROUTES
    Route::prefix('jadwal-reservasi')->name('jadwal_reservasi.')->group(function () {
        Route::get('/', [AdminJadwalReservasiController::class, 'index'])->name('index');
        Route::get('/export', [AdminJadwalReservasiController::class, 'export'])->name('export');
        Route::get('/{id}', [AdminJadwalReservasiController::class, 'show'])->name('show');
        Route::post('/{id}/status', [AdminJadwalReservasiController::class, 'updateStatus'])->name('update-status');
    });

    // PEMBAYARAN RESERVASI ROUTES
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/{id}/proses', [PembayaranController::class, 'show'])->name('show');
        Route::put('/{id}/proses', [PembayaranController::class, 'proses'])->name('proses');
        Route::get('/riwayat', [PembayaranController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{id}', [PembayaranController::class, 'detailRiwayat'])->name('riwayat.detail');
        Route::get('/{id}/struk', [PembayaranController::class, 'printStruk'])->name('struk');
        Route::delete('/{id}/batal', [PembayaranController::class, 'batal'])->name('batal');
    });

    // TREATMENT ROUTES
    Route::prefix('treatment')->name('treatment.')->group(function () {
        Route::get('/', [AdminTreatmentController::class, 'index'])->name('index');
        Route::get('/create', [AdminTreatmentController::class, 'create'])->name('add');
        Route::post('/', [AdminTreatmentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminTreatmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminTreatmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminTreatmentController::class, 'destroy'])->name('destroy');
        Route::get('/export', [AdminTreatmentController::class, 'export'])->name('export');
    });

    // PROMO ROUTES
    Route::prefix('promo')->name('promo.')->group(function () {
        Route::get('/', [AdminPromoController::class, 'index'])->name('index');
        Route::get('/create', [AdminPromoController::class, 'create'])->name('add');
        Route::post('/', [AdminPromoController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminPromoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminPromoController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPromoController::class, 'destroy'])->name('destroy');
        Route::get('/export', [AdminPromoController::class, 'export'])->name('export');
    });

    // STOK OBAT ROUTES
    Route::prefix('stok-obat')->name('stok-obat.')->group(function () {
        Route::get('/', [AdminStokObatController::class, 'index'])->name('index');
        Route::get('/create', [AdminStokObatController::class, 'create'])->name('add');
        Route::post('/', [AdminStokObatController::class, 'store'])->name('store');
        Route::get('/export', [AdminStokObatController::class, 'export'])->name('export');
        Route::get('/{id}/edit', [AdminStokObatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminStokObatController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminStokObatController::class, 'destroy'])->name('destroy');
    });

    // SUPPLIER ROUTES
    Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', [AdminSupplierController::class, 'index'])->name('index');
        Route::get('/create', [AdminSupplierController::class, 'create'])->name('add');
        Route::post('/', [AdminSupplierController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminSupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminSupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminSupplierController::class, 'destroy'])->name('destroy');
    });

    // PEMBELIAN OBAT ROUTES
    Route::prefix('pembelian-obat')->name('pembelian_obat.')->group(function () {
        Route::get('/', [AdminPembelianObatController::class, 'index'])->name('index');
        Route::get('/create', [AdminPembelianObatController::class, 'create'])->name('add');
        Route::post('/', [AdminPembelianObatController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminPembelianObatController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminPembelianObatController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminPembelianObatController::class, 'destroy'])->name('destroy');
    });

    // Note: Admin TIDAK bisa akses Pengeluaran, Pendapatan, dan Laporan Penjualan
});

// API endpoint untuk user
Route::get('/api/available-slots', [JadwalOperasionalController::class, 'getAvailableSlots'])
    ->name('api.available-slots');

// ============================================
// USER (PASIEN) ROUTES
// ============================================
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Reservasi
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/', [ReservationController::class, 'myReservations'])->name('my');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/get-time-slots', [ReservationController::class, 'getAvailableTimeSlots'])->name('get-time-slots');
        Route::get('/get-jadwal', [ReservationController::class, 'getJadwalByDate'])->name('get-jadwal');
    });
});