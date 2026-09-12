<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbDocument extends Model
{
    use Concerns\HasVisibilityCondition;

    public const TYPES = [
        'pdf' => 'PDF',
        'jpg' => 'JPG',
        'png' => 'PNG',
        'webp' => 'WebP',
    ];

    protected $fillable = [
        'jenjang', 'label', 'deskripsi', 'wajib', 'urut', 'aktif',
        'allowed', 'max_kb', 'compress',
        'visible_if_field', 'visible_if_operator', 'visible_if_value',
    ];

    protected function casts(): array
    {
        return [
            'wajib' => 'boolean',
            'aktif' => 'boolean',
            'allowed' => 'array',
            'compress' => 'boolean',
            'visible_if_value' => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeForJenjang($query, string $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urut')->orderBy('id');
    }

    public function docKey(): string
    {
        return 'doc_'.$this->id;
    }

    public function allowedMimes(): array
    {
        $map = ['pdf' => 'pdf', 'jpg' => 'jpg,jpeg', 'png' => 'png', 'webp' => 'webp'];

        $out = [];
        foreach ((array) ($this->allowed ?: array_keys(self::TYPES)) as $t) {
            if (isset($map[$t])) {
                $out[] = $map[$t];
            }
        }

        return explode(',', implode(',', $out));
    }

    public function isImageOnly(): bool
    {
        return ! in_array('pdf', (array) ($this->allowed ?: array_keys(self::TYPES)), true);
    }
}
