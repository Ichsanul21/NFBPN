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

    protected $fillable = [
        'jenjang', 'key', 'label', 'type', 'options',
        'is_required', 'sort_order', 'is_core',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'is_core' => 'boolean',
        ];
    }

    public function scopeForJenjang($query, string $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
