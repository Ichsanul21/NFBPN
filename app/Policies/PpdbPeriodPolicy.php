<?php

namespace App\Policies;

use App\Models\User;

class PpdbPeriodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ppdb.periods') || $user->hasRole('kepala-sekolah');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('ppdb.periods');
    }

    public function update(User $user): bool
    {
        return $user->can('ppdb.periods');
    }

    public function delete(User $user): bool
    {
        return false;
    }
}
