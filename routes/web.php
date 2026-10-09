<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublikasiController;
use Illuminate\Support\Facades\Route;

// Beranda: arahkan ke daftar publikasi (jika belum login, otomatis ke halaman login)
Route::redirect('/', '/publikasi')->name('home');

// Login & register (hanya untuk tamu)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

// Halaman yang wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // saran pencarian judul (harus sebelum resource agar tidak bentrok dengan /publikasi/{id})
    Route::get('/publikasi/cari', [PublikasiController::class, 'cari'])->name('publikasi.cari');

    // CRUD: index, create, store, edit, update, destroy
    Route::resource('publikasi', PublikasiController::class)->except('show');

    Route::view('/galeri', 'galeri')->name('galeri');
});
