<?php

namespace App\Policies;

use App\Models\User;

class PpdbFormFieldPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ppdb.fields');
    }

    public function view(User $user): bool
    {
        return $user->can('ppdb.fields');
    }

    public function create(User $user): bool
    {
        return $user->can('ppdb.fields');
    }

    public function update(User $user): bool
    {
        return $user->can('ppdb.fields');
    }

    public function delete(User $user): bool
    {
        return $user->can('ppdb.fields');
    }
}
