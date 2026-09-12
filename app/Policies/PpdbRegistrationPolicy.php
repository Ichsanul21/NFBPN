<?php

namespace App\Policies;

use App\Models\PpdbRegistration;
use App\Models\User;

class PpdbRegistrationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ppdb.registrations') || $user->hasRole('kepala-sekolah');
    }

    public function create(User $user): bool
    {
        return $user->can('ppdb.registrations');
    }

    public function view(User $user, PpdbRegistration $registration): bool
    {
        return $user->can('ppdb.registrations')
            || $user->hasRole('kepala-sekolah')
            || $registration->user_id === $user->id;
    }

    public function update(User $user, PpdbRegistration $registration): bool
    {
        if ($registration->user_id === $user->id && in_array($registration->status, ['terkirim', 'verifikasi'], true)) {
            return true;
        }

        return $user->can('ppdb.registrations');
    }

    public function delete(User $user): bool
    {
        return false;
    }
}
