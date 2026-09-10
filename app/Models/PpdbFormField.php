<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbFormField extends Model
{
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

    public function hasCondition(): bool
    {
        return ! empty($this->visible_if_field) && ! empty($this->visible_if_operator);
    }

    /**
     * Kandidat pemicu: field sejenjang tanpa kondisi sendiri (mencegah siklus,
     * maksimal 1 level) dan bukan tipe file.
     */
    public static function triggerCandidates(string $jenjang, ?int $exceptId = null)
    {
        return static::forJenjang($jenjang)->ordered()->get()->filter(
            fn (self $f) => ! $f->hasCondition()
                && $f->type !== 'file'
                && $f->id !== $exceptId
        )->values();
    }

    /**
     * Evaluasi apakah field tampil untuk jawaban yang diberikan.
     * $answers: array key => value (string|array|null).
     */
    public function isVisibleFor(array $answers): bool
    {
        if (! $this->hasCondition()) {
            return true;
        }

        $actual = $answers[$this->visible_if_field] ?? null;
        $expected = $this->visible_if_value;

        return match ($this->visible_if_operator) {
            'equals' => $this->equalsValue($actual, $expected),
            'not_equals' => ! $this->equalsValue($actual, $expected),
            'in' => count(array_intersect((array) $actual, (array) $expected)) > 0,
            'not_in' => count(array_intersect((array) $actual, (array) $expected)) === 0,
            'filled' => filled($actual),
            'empty' => blank($actual),
            default => true,
        };
    }

    protected function equalsValue(mixed $actual, mixed $expected): bool
    {
        if (is_array($actual)) {
            return in_array($expected, $actual, true)
                || (is_array($expected) && count(array_intersect($actual, $expected)) > 0);
        }

        return ((string) $actual) === ((string) (is_array($expected) ? ($expected[0] ?? '') : $expected));
    }
}
