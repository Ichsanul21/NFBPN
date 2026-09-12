<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbFormField extends Model
{
    use Concerns\HasVisibilityCondition;

    public const TYPES = [
        'text' => 'Teks singkat',
        'textarea' => 'Teks panjang',
        'number' => 'Angka',
        'date' => 'Tanggal',
        'select' => 'Pilihan (dropdown)',
        'radio' => 'Pilihan (radio)',
        'checkbox' => 'Centang banyak',
        'file' => 'Unggah berkas',
    ];

    public const OPERATORS = [
        'equals' => 'sama dengan',
        'not_equals' => 'tidak sama dengan',
        'in' => 'salah satu dari',
        'not_in' => 'bukan salah satu dari',
        'filled' => 'diisi',
        'empty' => 'kosong',
    ];

    protected $fillable = [
        'jenjang', 'key', 'label', 'section', 'type', 'options',
        'is_required', 'sort_order', 'is_core', 'is_active',
        'visible_if_field', 'visible_if_operator', 'visible_if_value',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
            'visible_if_value' => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForJenjang($query, string $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Kandidat pemicu: field sejenjang tanpa kondisi sendiri (mencegah siklus,
     * maksimal 1 level) dan bukan tipe file.
     */
    public static function triggerCandidates(string $jenjang, ?int $exceptId = null)
    {
        return static::conditionTriggers($jenjang)->filter(
            fn (self $f) => $f->id !== $exceptId
        )->values();
    }
}
