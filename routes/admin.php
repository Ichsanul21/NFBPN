<?php

use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsImageController;
use App\Http\Controllers\Admin\PpdbExportController;
use App\Http\Controllers\Admin\PpdbFieldController;
use App\Http\Controllers\Admin\PpdbPeriodController;
use App\Http\Controllers\Admin\PpdbRegistrationController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'permission:dashboard.view'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('news', NewsController::class);
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::resource('agendas', AgendaController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::post('news/image', [NewsImageController::class, 'upload'])->name('news.image');

    Route::resource('periods', PpdbPeriodController::class)->except(['show']);
    Route::get('fields', [PpdbFieldController::class, 'index'])->name('fields.index');
    Route::post('fields/quick', [PpdbFieldController::class, 'quick'])->name('fields.quick');
    Route::post('fields/reorder', [PpdbFieldController::class, 'reorder'])->name('fields.reorder');
    Route::put('fields/{field}', [PpdbFieldController::class, 'update'])->name('fields.update');
    Route::delete('fields/{field}', [PpdbFieldController::class, 'destroy'])->name('fields.destroy');
    Route::resource('registrations', PpdbRegistrationController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('registrations-export', PpdbExportController::class)->name('registrations.export');

    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::resource('users', UserController::class)->except(['show']);

    Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');
});
