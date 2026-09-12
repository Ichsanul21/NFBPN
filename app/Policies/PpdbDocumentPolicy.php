<?php

namespace App\Policies;

use App\Models\User;

class PpdbDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ppdb.documents') || $user->hasRole('kepala-sekolah');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('ppdb.documents');
    }

    public function update(User $user): bool
    {
        return $user->can('ppdb.documents');
    }

    public function delete(User $user): bool
    {
        return $user->can('ppdb.documents');
    }
}
