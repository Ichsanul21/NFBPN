<?php

namespace App\Policies;

use App\Models\User;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('messages.manage');
    }

    public function view(User $user): bool
    {
        return $user->can('messages.manage');
    }

    public function update(User $user): bool
    {
        return $user->can('messages.manage');
    }

    public function delete(User $user): bool
    {
        return $user->can('messages.manage');
    }
}
