<?php

namespace App\Policies;

use App\Models\User;

abstract class ContentPolicy
{
    protected string $managePermission = '';

    public function viewAny(User $user): bool
    {
        return $user->can($this->managePermission) || $user->hasRole('kepala-sekolah');
    }

    public function view(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can($this->managePermission);
    }

    public function update(User $user): bool
    {
        return $user->can($this->managePermission);
    }

    public function delete(User $user): bool
    {
        return $user->can($this->managePermission);
    }
}
