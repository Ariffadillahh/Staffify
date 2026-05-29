<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalWawancaraController;
use App\Http\Controllers\KadivDashboardController;
use App\Http\Controllers\KadivManagementController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PendaftaransController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\POMonitoringController;
use App\Http\Controllers\ProfileMatchingController;
use App\Http\Controllers\ProkersController;
use Illuminate\Support\Facades\Route;

Route::get('/setup-proker', [AuthController::class, 'showSetup']);
Route::post('/setup-proker', [AuthController::class, 'setup']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/publik/divisi', [PendaftaransController::class, 'create']);
Route::post('/publik/daftar', [PendaftaransController::class, 'store']);
Route::get('/publik/daftar/{id}/pilih-jadwal', [PendaftaransController::class, 'pilihJadwal']);
Route::post('/publik/daftar/{id}/simpan-jadwal', [PendaftaransController::class, 'simpanJadwal']);
Route::get('/publik/pengumuman', [PendaftaransController::class, 'pengumuman']);
Route::get('/publik/status-pendaftaran', [PendaftaransController::class, 'statusPendaftaran']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/password/update', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/jadwal', [JadwalWawancaraController::class, 'index']);
    Route::post('/jadwal/{id}/toggle', [JadwalWawancaraController::class, 'toggleStatus']);
    Route::post('/jadwal/generate', [JadwalWawancaraController::class, 'generate']);
    Route::post('/jadwal/bulk-update', [JadwalWawancaraController::class, 'updateBulk']);

    Route::get('/kadiv/dashboard', [KadivDashboardController::class, 'index']);
    Route::post('/kadiv/dashboard/link', [KadivDashboardController::class, 'updateLink']);
    Route::delete('/kadiv/dashboard/link', [KadivDashboardController::class, 'deleteLink']);

    // RUTE PO: Manajemen Generate Akun Kadiv & Input Divisi Massal
    Route::get('/po/divisi-tanpa-kadiv', [KadivManagementController::class, 'index']);
    Route::post('/po/divisi/bulk-store', [KadivManagementController::class, 'storeDivisi']);
    Route::post('/po/kadiv/generate', [KadivManagementController::class, 'storeKadiv']);

    // Rute Input Evaluasi Nilai untuk Kadiv
    Route::get('/penilaian/kandidat/{id}', [PenilaianController::class, 'create']);
    Route::post('/penilaian/kandidat/{id}', [PenilaianController::class, 'store']);

    // Rute Monitoring untuk PO / VPO
    Route::get('/po/monitoring/jadwal', [POMonitoringController::class, 'jadwalWawancara']);
    Route::get('/po/monitoring/kandidat-ranking', [POMonitoringController::class, 'kandidat']);


    // RUTE KADIV: Perangkingan Profile Matching
    Route::get('/kadiv/profile-matching', [ProfileMatchingController::class, 'index']);
    Route::post('/kadiv/profile-matching/keputusan/{id}', [ProfileMatchingController::class, 'simpanKeputusan']);
    Route::get('/kadiv/profile-matching/detail/{id}', [ProfileMatchingController::class, 'detailKalkulasi']);

    // RUTE PO: Kontrol Oprec Global & Update Profil Kadiv
    Route::get('/proker', [ProkersController::class, 'index'])->name('proker.index');
    Route::post('/po/update-status-recruitment', [ProkersController::class, 'updateStatusRecruitment']);
    Route::post('/po/divisi/update-kadiv/{id}', [ProkersController::class, 'updateKadiv']);

    Route::get('/kriteria', [KriteriaController::class, 'index']);
    Route::post('/kriteria', [KriteriaController::class, 'store']);
    Route::put('/kriteria/{id}', [KriteriaController::class, 'update']);
    Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy']);
});
