<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\SahamAlternatifController;
use App\Http\Controllers\Admin\NilaiFundamentalController;
use App\Http\Controllers\Investor\InvestorDashboardController;
use App\Http\Controllers\Investor\HasilAnalisisController;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\CocosoController;

// Halaman Utama: Redirect cerdas berdasarkan status autentikasi & role
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('investor.dashboard');
    }
    return redirect()->route('login');
});

// Autentikasi (Tamu / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (Hanya User yang Login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Modul Admin (Dilindungi Auth & Hak Akses Role Admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('kriteria', KriteriaController::class);
    Route::resource('saham', SahamAlternatifController::class);
    
    Route::get('nilai-fundamental', [NilaiFundamentalController::class, 'index'])->name('nilai_fundamental.index');
    Route::post('nilai-fundamental/import', [NilaiFundamentalController::class, 'import'])->name('nilai_fundamental.import');
    Route::delete('nilai-fundamental/delete-all', [NilaiFundamentalController::class, 'deleteAll'])->name('nilai_fundamental.delete_all');
    Route::get('nilai-fundamental/download-template', [NilaiFundamentalController::class, 'downloadTemplate'])->name('nilai_fundamental.download_template');
    Route::get('nilai-fundamental/{saham}/edit', [NilaiFundamentalController::class, 'edit'])->name('nilai_fundamental.edit');
    Route::put('nilai-fundamental/{saham}', [NilaiFundamentalController::class, 'update'])->name('nilai_fundamental.update');
});

// Modul Investor (Dilindungi Auth & Hak Akses Role Investor)
Route::prefix('investor')->name('investor.')->middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kuesioner', [InvestorDashboardController::class, 'showKuesioner'])->name('kuesioner');
});

// Algoritma Core & Hasil (Bisa diakses oleh Investor)
Route::middleware(['auth', 'role:investor'])->group(function () {
    Route::post('/ahp/calculate', [AhpController::class, 'calculate'])->name('ahp.calculate');
    Route::get('/cocoso/calculate/{riwayat_id}', [CocosoController::class, 'calculate'])->name('cocoso.calculate');
    Route::get('/hasil/{riwayat_id}', [HasilAnalisisController::class, 'show'])->name('hasil.show');
});

