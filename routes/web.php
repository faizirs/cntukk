<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelolaSiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KompetensiKeahlianController;
use App\Http\Controllers\ManagementController;

Route::get('/', [AuthController::class, 'formLogin']);
Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('admin', AdminController::class); 
    Route::resource('kompetensi-keahlian', KompetensiKeahlianController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('spp', SppController::class);
    Route::resource('pembayaran', PembayaranController::class)->except(['show']);
    Route::get('/pembayaran/export', [PembayaranController::class, 'exportExcel'])->name('pembayaran.export');
    Route::resource('kelola-siswa', KelolaSiswaController::class);
    Route::resource('management', ManagementController::class);
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    
    Route::resource('siswa', SiswaController::class);
});


