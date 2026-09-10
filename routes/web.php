<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
Route::get('/unit/{slug}', [PageController::class, 'unit'])->name('unit');
Route::get('/berita', [PageController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [PageController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/galeri', [PageController::class, 'galeri'])->name('galeri');
Route::get('/ppdb', [PageController::class, 'ppdb'])->name('ppdb');
Route::get('/ppdb/status', [PageController::class, 'ppdbStatus'])->name('ppdb.status');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
