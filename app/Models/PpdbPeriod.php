<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbPeriod extends Model
{
    protected $fillable = [
        'name', 'jenjang', 'starts_on', 'ends_on', 'quota', 'note', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(PpdbRegistration::class, 'period_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isOpen(): bool
    {
        return $this->is_active && today()->between($this->starts_on, $this->ends_on);
    }

    public function formFields()
    {
        return PpdbFormField::forJenjang($this->jenjang)->ordered()->get();
    }
}
