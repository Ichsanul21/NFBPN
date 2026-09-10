<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\PpdbRegistration;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        if ($user->can('ppdb.registrations') || $user->hasRole('kepala-sekolah')) {
            $data['regByStatus'] = PpdbRegistration::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->pluck('total', 'status')->all();
            $data['regTotal'] = PpdbRegistration::count();
            $data['regByJenjang'] = PpdbRegistration::select('jenjang', DB::raw('count(*) as total'))
                ->groupBy('jenjang')->pluck('total', 'jenjang')->all();
            $data['recentRegs'] = PpdbRegistration::with('period')->latest()->take(5)->get();
        }

        if ($user->can('news.manage')) {
            $data['newsCount'] = News::count();
            $data['recentNews'] = News::with('category')->latest()->take(5)->get();
        }

        if ($user->can('messages.manage')) {
            $data['unreadMessages'] = ContactMessage::unread()->count();
        }

        $data['upcomingAgendas'] = Agenda::upcoming()->take(4)->get();

        return view('admin.dashboard', $data);
    }
}
