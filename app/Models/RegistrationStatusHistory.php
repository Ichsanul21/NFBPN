<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationStatusHistory extends Model
{
    protected $fillable = [
        'registration_id', 'from_status', 'to_status', 'note', 'changed_by',
    ];

    public function registration()
    {
        return $this->belongsTo(PpdbRegistration::class, 'registration_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
