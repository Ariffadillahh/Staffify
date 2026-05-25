<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisisController;
use App\Http\Controllers\JadwalWawancaraController;
use App\Http\Controllers\KadivDashboardController;
use App\Http\Controllers\KadivManagementController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PendaftaransController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\POMonitoringController;
use App\Http\Controllers\ProfileMatchingController;
use App\Http\Controllers\ProkersController;
use App\Models\Proker;

Route::get('/', function () {
    $isSetup = User::where('role', 'po')->exists();
    $proker = Proker::latest()->first();

    return view('welcome', compact('isSetup', 'proker'));
})->name('landing');

Route::get('/setup-proker', [AuthController::class, 'showSetup'])->name('setup.create');
Route::post('/setup-proker', [AuthController::class, 'setup'])->name('setup.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Publik (Pendaftaran Kandidat)
|--------------------------------------------------------------------------
*/
Route::get('/daftar', [PendaftaransController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [PendaftaransController::class, 'store'])->name('daftar.store');

Route::get('/daftar/{id}/pilih-jadwal', [PendaftaransController::class, 'pilihJadwal'])->name('daftar.jadwal');
Route::post('/daftar/{id}/simpan-jadwal', [PendaftaransController::class, 'simpanJadwal'])->name('daftar.simpan_jadwal');

Route::view('/pendaftaran-sukses', 'publik.sukses')->name('daftar.sukses');

Route::get('/pengumuman', [PendaftaransController::class, 'pengumuman'])->name('pengumuman.index');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi & Admin (PO / VPO / KADIV)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Rute Khusus KADIV
    Route::middleware('role:kadiv')->group(function () {

        // 1. Manajemen Jadwal Wawancara (Ubah Merah/Putih)
        Route::get('/jadwal', [JadwalWawancaraController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal/{id}/toggle', [JadwalWawancaraController::class, 'toggleStatus'])->name('jadwal.toggle');

        // 2. Input Penilaian Wawancara
        Route::get('/penilaian/{pendaftaran}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian/{pendaftaran}', [PenilaianController::class, 'store'])->name('penilaian.store');

        // 3. Kalkulasi Profile Matching
        Route::get('/hasil-profile-matching', [ProfileMatchingController::class, 'index'])->name('pm.index');

        Route::post('/jadwal/generate', [JadwalWawancaraController::class, 'generate'])->name('jadwal.generate');
        // Tambahkan di bawah route jadwal.generate atau jadwal.toggle
        Route::post('/jadwal/bulk-update', [JadwalWawancaraController::class, 'updateBulk'])->name('jadwal.bulk_update');

        // Dashboard Divisi
        Route::get('/dashboard-divisi', [KadivDashboardController::class, 'index'])->name('kadiv.dashboard');
        Route::post('/dashboard-divisi/link', [KadivDashboardController::class, 'updateLink'])->name('kadiv.update_link');
        Route::delete('/dashboard-divisi/link', [KadivDashboardController::class, 'deleteLink'])->name('kadiv.delete_link');

        Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
        Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
        Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');

        Route::post('/pm/keputusan/{id}', [ProfileMatchingController::class, 'simpanKeputusan'])->name('pm.keputusan');
    });

    // Rute Khusus PO / VPO
    Route::middleware('role:po,vpo')->group(function () {
        Route::get('/proker', [ProkersController::class, 'index'])->name('proker.index');
        Route::post('/proker', [ProkersController::class, 'store'])->name('proker.store');

        Route::post('/divisi/bulk-store', [KadivManagementController::class, 'storeDivisi'])->name('divisi.bulk_store');
        Route::get('/kadiv/generate', [KadivManagementController::class, 'index'])->name('kadiv.generate_page');
        Route::post('/kadiv/generate', [KadivManagementController::class, 'storeKadiv'])->name('kadiv.store');

        Route::get('/po/monitoring-jadwal', [POMonitoringController::class, 'jadwalWawancara'])->name('po.monitoring.jadwal');
        Route::get('/po/kandidat', [POMonitoringController::class, 'kandidat'])->name('po.kandidat');

        Route::post('/po/update-status-recruitment', [ProkersController::class, 'updateStatusRecruitment'])->name('po.status_recruitment.update');

        Route::post('/po/divisi/update-kadiv/{id}', [ProkersController::class, 'updateKadiv'])->name('po.divisi.update_kadiv');
    });

    Route::put('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/pm/detail/{id}', [ProfileMatchingController::class, 'detailKalkulasi'])->name('pm.detail');
});
