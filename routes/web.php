<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman publik
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PageController::class, 'tentang'])->name('tentang');
Route::get('/unit/{slug}', [PageController::class, 'unit'])->name('unit');
Route::get('/berita', [PageController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [PageController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/galeri', [PageController::class, 'galeri'])->name('galeri');
Route::get('/ppdb', [PageController::class, 'ppdb'])->name('ppdb');
Route::post('/ppdb', [PageController::class, 'ppdbStore'])
    ->middleware('throttle:10,1')->name('ppdb.store');
Route::get('/ppdb/status', [PageController::class, 'ppdbStatus'])->name('ppdb.status');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PageController::class, 'kontakStore'])
    ->middleware('throttle:10,1')->name('kontak.store');

// Redirect dashboard bawaan Breeze sesuai role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->can('dashboard.view')) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('portal.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Portal orang tua
    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
    Route::get('/portal/pendaftaran/{registration}', [PortalController::class, 'show'])->name('portal.show');
    Route::post('/portal/pendaftaran/{registration}/berkas', [PortalController::class, 'uploadBerkas'])
        ->middleware('throttle:10,1')->name('portal.berkas');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
