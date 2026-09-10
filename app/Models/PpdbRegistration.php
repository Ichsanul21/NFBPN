<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    public const STATUSES = [
        'terkirim' => 'Terkirim',
        'verifikasi' => 'Verifikasi Berkas',
        'observasi' => 'Observasi / Tes',
        'lolos' => 'Lolos',
        'cadangan' => 'Cadangan',
        'tidak_lolos' => 'Tidak Lolos',
        'daftar_ulang' => 'Daftar Ulang',
        'aktif' => 'Siswa Aktif',
    ];

    protected $fillable = [
        'registration_no', 'period_id', 'user_id', 'jenjang',
        'child_name', 'child_birthdate', 'gender', 'parent_name',
        'whatsapp', 'answers', 'status', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'child_birthdate' => 'date',
            'answers' => 'array',
        ];
    }

    public function period()
    {
        return $this->belongsTo(PpdbPeriod::class, 'period_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function histories()
    {
        return $this->hasMany(RegistrationStatusHistory::class, 'registration_id')->latest();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
