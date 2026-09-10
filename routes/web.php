<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\PengajuanPklController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StatusPendaftaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Auth routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('home'))->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/{division}', [LowonganController::class, 'show'])->name('lowongan.show');
    Route::get('/pengajuan', [PengajuanPklController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/check-availability', [PengajuanPklController::class, 'checkAvailability'])->name('pengajuan.check-availability');
    Route::post('/pengajuan', [PengajuanPklController::class, 'store'])->name('pengajuan.store');
    Route::middleware('ensure.kelompok')->group(function () {
        Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index');
        Route::post('/kelompok/anggota', [KelompokController::class, 'storeAnggota'])->name('kelompok.anggota.store');
    });
    Route::get('/status', [StatusPendaftaranController::class, 'index'])->name('status.index');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // Admin Portal Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => \Inertia\Inertia::render('Admin/Dashboard', ['activeNav' => 'admin.dashboard']))->name('dashboard');

        Route::get('/lowongan', fn () => \Inertia\Inertia::render('Admin/Lowongan/Index', ['activeNav' => 'admin.lowongan']))->name('lowongan.index');
        Route::get('/lowongan/create', fn () => \Inertia\Inertia::render('Admin/Lowongan/Create', ['activeNav' => 'admin.lowongan']))->name('lowongan.create');
        Route::get('/lowongan/{lowongan}', fn () => \Inertia\Inertia::render('Admin/Lowongan/Show', ['activeNav' => 'admin.lowongan']))->name('lowongan.show');
        Route::get('/lowongan/{lowongan}/edit', fn () => \Inertia\Inertia::render('Admin/Lowongan/Edit', ['activeNav' => 'admin.lowongan']))->name('lowongan.edit');

        Route::get('/pengajuan', fn () => \Inertia\Inertia::render('Admin/Pengajuan/Index', ['activeNav' => 'admin.pengajuan']))->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', fn () => \Inertia\Inertia::render('Admin/Pengajuan/Show', ['activeNav' => 'admin.pengajuan']))->name('pengajuan.show');

        Route::get('/peserta', fn () => \Inertia\Inertia::render('Admin/Peserta/Index', ['activeNav' => 'admin.peserta']))->name('peserta.index');

        Route::get('/profile', fn () => \Inertia\Inertia::render('Admin/Profile/Edit', ['activeNav' => 'admin.profile']))->name('profile.edit');
    });

    // Super Admin Portal Routes (frontend-only review, no role middleware yet)
    Route::prefix('super-admin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', fn () => \Inertia\Inertia::render('SuperAdmin/Dashboard', ['activeNav' => 'superadmin.dashboard']))->name('dashboard');

        Route::get('/instansi', fn () => \Inertia\Inertia::render('SuperAdmin/Instansi/Index', ['activeNav' => 'superadmin.instansi']))->name('instansi.index');
        Route::get('/instansi/{instansi}', fn () => \Inertia\Inertia::render('SuperAdmin/Instansi/Show', ['activeNav' => 'superadmin.instansi']))->name('instansi.show');

        Route::get('/undangan', fn () => \Inertia\Inertia::render('SuperAdmin/Undangan/Index', ['activeNav' => 'superadmin.undangan']))->name('undangan.index');

        Route::get('/audit-log', fn () => \Inertia\Inertia::render('SuperAdmin/AuditLog/Index', ['activeNav' => 'superadmin.audit-log']))->name('audit-log.index');
    });
});

require __DIR__.'/auth.php';
