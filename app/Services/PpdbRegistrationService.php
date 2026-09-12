<?php

namespace App\Services;

use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class PpdbRegistrationService
{
    public static function fieldsFor(string $jenjang)
    {
        return PpdbFormField::forJenjang($jenjang)->active()->ordered()->get();
    }

    /**
     * Bangun aturan validasi hanya untuk field yang tampil (evaluasi server).
     */
    public static function buildRules($fields, array $submitted): array
    {
        $rules = [];
        foreach ($fields->filter(fn ($f) => $f->isVisibleFor($submitted))->values() as $f) {
            $key = "answers.{$f->key}";
            $base = match ($f->type) {
                'number' => 'numeric',
                'date' => 'date',
                'file' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
                'checkbox' => 'array',
                default => 'string|max:2000',
            };
            $rules[$key] = ($f->is_required ? 'required' : 'nullable').'|'.$base;
            if (in_array($f->type, ['select', 'radio'], true) && $f->options) {
                $rules[$key] .= '|in:'.implode(',', $f->options);
            }
            if ($f->type === 'checkbox' && $f->options) {
                $rules[$key.'.'] = 'in:'.implode(',', $f->options);
            }
        }

        return $rules;
    }

    public static function visibleFields($fields, array $submitted)
    {
        return $fields->filter(fn ($f) => $f->isVisibleFor($submitted))->values();
    }

    public static function extractAnswers(Request $request, $visibleFields, array $validated): array
    {
        $answers = $validated['answers'] ?? [];
        foreach ($visibleFields as $f) {
            if ($f->type === 'file' && $request->hasFile("answers.{$f->key}")) {
                $answers[$f->key] = $request->file("answers.{$f->key}")->store('ppdb/berkas', 'public');
            }
        }

        return $answers;
    }

    public static function nextNumber(): string
    {
        do {
            $no = 'NF-'.now()->format('Y').'-'.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (PpdbRegistration::where('registration_no', $no)->exists());

        return $no;
    }

    public static function createRegistration(
        User $user,
        PpdbPeriod $period,
        array $data,
        array $answers,
        ?User $assistant = null,
        ?string $komitmenTeks = null,
    ): PpdbRegistration {
        $reg = PpdbRegistration::create([
            'registration_no' => static::nextNumber(),
            'period_id' => $period->id,
            'user_id' => $user->id,
            'jenjang' => $period->jenjang,
            'child_name' => \App\Support\Sanitize::name($data['child_name']),
            'child_birthdate' => $data['child_birthdate'],
            'gender' => $data['gender'] ?? null,
            'parent_name' => \App\Support\Sanitize::name($data['parent_name']),
            'whatsapp' => \App\Support\Sanitize::phone($data['whatsapp']),
            'answers' => $answers,
            'status' => 'terkirim',
            'dibantu_tu' => $assistant !== null,
            'assisted_by' => $assistant?->id,
            'komitmen_teks' => $komitmenTeks,
            'komitmen_at' => $komitmenTeks ? now() : null,
        ]);

        if ($assistant) {
            $reg->histories()->create([
                'from_status' => null,
                'to_status' => 'terkirim',
                'note' => 'Didaftarkan dengan bantuan TU ('.$assistant->name.').',
                'changed_by' => $assistant->id,
            ]);
        }

        return $reg;
    }
}
