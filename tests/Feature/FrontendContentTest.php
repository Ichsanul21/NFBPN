<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_galeri_menampilkan_foto_asli_dari_database(): void
    {
        Gallery::create([
            'title' => 'Market Day',
            'category' => 'Kegiatan',
            'unit' => 'SDIT',
            'path' => 'galeri/uji.webp',
            'thumb_path' => 'galeri/uji-thumb.webp',
        ]);

        $res = $this->get('/galeri');

        $res->assertOk();
        $res->assertSee('storage/galeri/uji-thumb.webp', false);
        $res->assertSee('data-gal-src="http', false);
        $res->assertDontSee('Placeholder. Foto asli', false);
    }

    public function test_galeri_kosong_menampilkan_state_kosong(): void
    {
        $res = $this->get('/galeri');

        $res->assertOk();
        $res->assertSee('Belum ada foto.', false);
    }

    public function test_footer_dan_kontak_membaca_pengaturan(): void
    {
        SiteSetting::set('telepon', '(0542) 999-888', 'kontak');
        SiteSetting::set('alamat', 'Jl. Uji Coba No. 9', 'kontak');

        $home = $this->get('/');
        $home->assertOk();
        $home->assertSee('(0542) 999-888', false);
        $home->assertSee('Jl. Uji Coba No. 9', false);

        $kontak = $this->get('/kontak');
        $kontak->assertOk();
        $kontak->assertSee('(0542) 999-888', false);
    }
}
