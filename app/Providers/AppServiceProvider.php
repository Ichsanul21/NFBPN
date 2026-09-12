<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(fn ($user) => $user->hasRole('super-admin') ? true : null);

        View::composer(['layouts.site', 'pages.kontak'], function ($view) {
            $view->with('site', [
                'alamat' => SiteSetting::get('alamat', 'Jl. Pendidikan No. 1, Balikpapan Selatan, Kalimantan Timur'),
                'telepon' => SiteSetting::get('telepon', '(0542) 123-456'),
                'email' => SiteSetting::get('email', 'info@nurulfikri-balikpapan.sch.id'),
                'whatsapp' => SiteSetting::get('whatsapp', '62542123456'),
                'jam' => SiteSetting::get('jam', 'Senin-Jumat, 07.00-16.00 WITA'),
                'instagram' => SiteSetting::get('instagram', '#'),
                'facebook' => SiteSetting::get('facebook', '#'),
                'youtube' => SiteSetting::get('youtube', '#'),
            ]);
        });
    }
}
