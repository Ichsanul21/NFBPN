<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbCommitment;
use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\User;
use App\Services\PpdbRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PpdbAssistedController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('viewAny', PpdbRegistration::class);

        $periods = PpdbPeriod::orderBy('starts_on', 'desc')->get();
        $period = $request->filled('period_id')
            ? PpdbPeriod::findOrFail($request->input('period_id'))
            : null;

        $fields = $conditions = [];
        if ($period) {
            $fields = PpdbRegistrationService::fieldsFor($period->jenjang);
            foreach ($fields as $f) {
                if ($f->hasCondition()) {
                    $conditions[$f->key] = [
                        'trigger' => $f->visible_if_field,
                        'op' => $f->visible_if_operator,
                        'value' => $f->visible_if_value,
                    ];
                }
            }
        }

        return view('admin.registrations.manual', [
            'periods' => $periods,
            'period' => $period,
            'fields' => $fields,
            'conditions' => $conditions,
            'commitment' => $period ? PpdbCommitment::where('jenjang', $period->jenjang)->first() : null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', PpdbRegistration::class);

        if ($request->filled('website')) {
            abort(403);
        }

        $period = PpdbPeriod::findOrFail($request->input('period_id'));
        $jenjang = $period->jenjang;

        $mode = $request->input('ortu_mode', 'baru');
        if ($mode === 'lama') {
            $request->validate(['ortu_email' => 'required|email|exists:users,email']);
            $user = User::where('email', $request->input('ortu_email'))->firstOrFail();
        } else {
            $account = $request->validate([
                'new_name' => 'required|string|max:255',
                'new_email' => 'required|email|max:255|unique:users,email',
                'new_whatsapp' => 'required|string|max:20',
                'new_password' => ['required', 'string', \App\Support\Passwords::rule()],
            ]);
            $user = User::create([
                'name' => \App\Support\Sanitize::name($account['new_name']),
                'email' => $account['new_email'],
                'password' => Hash::make($account['new_password']),
            ]);
            if (\Spatie\Permission\Models\Role::where('name', 'orang-tua')->exists()) {
                $user->assignRole('orang-tua');
            }
        }

        if (PpdbRegistration::where('user_id', $user->id)->where('period_id', $period->id)->exists()) {
            return back()->with('error', 'Akun ini sudah terdaftar pada periode tersebut.')->withInput();
        }

        $rules = [
            'period_id' => 'required|exists:ppdb_periods,id',
            'child_name' => 'required|string|max:255',
            'child_birthdate' => 'required|date|before:today',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'parent_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
        ];
        $fields = PpdbRegistrationService::fieldsFor($jenjang);
        $submitted = $request->input('answers', []);
        $visible = PpdbRegistrationService::visibleFields($fields, $submitted);
        $rules = array_merge($rules, PpdbRegistrationService::buildRules($fields, $submitted));

        $commitment = PpdbCommitment::where('jenjang', $jenjang)->first();
        if ($commitment?->teks) {
            $rules['komitmen'] = 'accepted';
        }

        $data = $request->validate($rules);
        $answers = PpdbRegistrationService::extractAnswers($request, $visible, $data);

        $reg = PpdbRegistrationService::createRegistration(
            $user, $period, $data, $answers, auth()->user(), $commitment?->teks
        );

        $msg = 'Pendaftaran manual tersimpan: '.$reg->registration_no.'.';
        if ($mode === 'baru') {
            $msg .= ' Kata sandi sementara akun ortu: '.$request->input('new_password').' (sampaikan via WA, minta segera diganti).';
        }

        return redirect()->route('admin.registrations.show', $reg)->with('success', $msg);
    }
}
