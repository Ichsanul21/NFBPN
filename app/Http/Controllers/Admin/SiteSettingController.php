<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SiteSetting::class);

        $settings = SiteSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize('update', SiteSetting::class);

        foreach ($request->except(['_token', '_method']) as $key => $value) {
            SiteSetting::set($key, is_string($value) ? trim($value) : $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
