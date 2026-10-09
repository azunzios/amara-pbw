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

// Halaman publik (Read: bebas dikunjungi & dilihat-lihat tanpa login)
Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
Route::get('/publikasi/cari', [PublikasiController::class, 'cari'])->name('publikasi.cari');
Route::view('/galeri', 'galeri')->name('galeri');

// Halaman & aksi yang wajib login (CUD + Logout)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CUD Publikasi: create, store, edit, update, destroy
    Route::get('/publikasi/create', [PublikasiController::class, 'create'])->name('publikasi.create');
    Route::post('/publikasi', [PublikasiController::class, 'store'])->name('publikasi.store');
    Route::get('/publikasi/{publikasi}/edit', [PublikasiController::class, 'edit'])->name('publikasi.edit');
    Route::put('/publikasi/{publikasi}', [PublikasiController::class, 'update'])->name('publikasi.update');
    Route::delete('/publikasi/{publikasi}', [PublikasiController::class, 'destroy'])->name('publikasi.destroy');
});
