<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SettingSistemController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiDetailController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Models\Reservasi;
use App\Models\transaksi;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProfileController;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
*/

//-------------------------------- AUTH ----------------------------------------------------
Route::prefix('/')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('indexAuth')->middleware('guest');
    Route::get('/create', [AuthController::class, 'create'])->name('registerAuth')->middleware('guest');
    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticateAuth');
    Route::post('/store', [AuthController::class, 'store'])->name('storeAuth');
    Route::post('/out-auth', [AuthController::class, 'out'])->name('outAuth');
});

//-------------------------------- DASHBOARD -----------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('indexDashboard');

    //-------------------------------- MANAJEMEN KARYAWAN --------------------------------------
    Route::prefix('karyawan')->middleware(['admin'])->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('indexKaryawan');
        Route::get('/create', [KaryawanController::class, 'create'])->name('createKaryawan');
        Route::get('/edit/{id}', [KaryawanController::class, 'edit'])->name('editKaryawan');
        Route::post('/store', [KaryawanController::class, 'store'])->name('storeKaryawan');
        Route::put('/update/{id}', [KaryawanController::class, 'update'])->name('updateKaryawan'); // Menggunakan PUT
        Route::delete('/delete/{id}', [KaryawanController::class, 'delete'])->name('deleteKaryawan');
    });

    //-------------------------------- PRODUK -------------------------------------------------
    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
        Route::middleware(['admin'])->group(function () {
            Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
            Route::post('/', [ProdukController::class, 'store'])->name('produk.store');
            Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
            Route::put('/{id}', [ProdukController::class, 'update'])->name('produk.update');
            Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        });
    });

    //-------------------------------- TRANSAKSI ----------------------------------------------
    Route::prefix('transaksi')->middleware(['admin'])->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
        Route::get('/export-excel', [TransaksiController::class, 'exportExcel'])->name('transaksi.export-excel');
    });

    //-------------------------------- TRANSAKSI DETAIL ---------------------------------------
    Route::prefix('transaksidetail')->middleware(['admin'])->group(function () {
        Route::get('/', [TransaksiDetailController::class, 'index'])->name('transaksidetail.index');
        Route::get('/create', [TransaksiDetailController::class, 'create'])->name('transaksidetail.create');
        Route::post('/', [TransaksiDetailController::class, 'store'])->name('transaksidetail.store');
        Route::get('/{id}/edit', [TransaksiDetailController::class, 'edit'])->name('transaksidetail.edit');
        Route::put('/{id}', [TransaksiDetailController::class, 'update'])->name('transaksidetail.update');
        Route::delete('/{id}', [TransaksiDetailController::class, 'destroy'])->name('transaksidetail.destroy');
        Route::get('/show/{id}', [TransaksiDetailController::class, 'show'])->name('transaksidetail.show');
    });

    //-------------------------------- POS (POINT OF SALE) ------------------------------------
    Route::prefix('pos')->middleware(['admin-karyawan'])->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('pos.index');
        Route::post('/', [PosController::class, 'store'])->name('pos.store');
        Route::get('/receipt/{id}', [PosController::class, 'printReceipt'])->name('pos.receipt');
    });

    //-------------------------------- LAPORAN PENJUALAN -------------------------------------
    Route::prefix('laporan-penjualan')->middleware(['admin-karyawan'])->group(function () {
        Route::get('/', [LaporanPenjualanController::class, 'index'])->name('laporanpenjualan.index');
        Route::get('/pdf', [LaporanPenjualanController::class, 'generatePdf'])->name('laporanpenjualan.pdf');
        Route::post('/store/{tanggal}', [LaporanPenjualanController::class, 'store'])->name('laporanpenjualan.store');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Raw Materials Management Routes
Route::middleware(['auth', 'admin-karyawan'])->group(function () {
    Route::get('raw-materials', [RawMaterialController::class, 'index'])->name('raw-materials.index');  // <-- Menambahkan rute untuk index
    Route::get('raw-materials/create', [RawMaterialController::class, 'create'])->name('raw-materials.create');
    Route::post('raw-materials', [RawMaterialController::class, 'store'])->name('raw-materials.store');
    Route::get('raw-materials/{rawMaterial}/edit', [RawMaterialController::class, 'edit'])->name('raw-materials.edit');
    Route::put('raw-materials/{rawMaterial}', [RawMaterialController::class, 'update'])->name('raw-materials.update');
    Route::delete('raw-materials/{rawMaterial}', [RawMaterialController::class, 'destroy'])->name('raw-materials.destroy');
    Route::post('raw-materials/{rawMaterial}/adjust-stock', [RawMaterialController::class, 'adjustStock'])->name('raw-materials.adjust-stock');
    Route::get('low-stock-materials', [RawMaterialController::class, 'lowStock'])->name('raw-materials.low-stock');
    Route::get('raw-materials/purchase', [RawMaterialController::class, 'purchase'])->name('raw-materials.purchase');
    Route::post('raw-materials/purchase', [RawMaterialController::class, 'storePurchase'])->name('raw-materials.purchase.store');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('raw-materials-report', [RawMaterialController::class, 'report'])->name('raw-materials.report');
});

