<?php

namespace App\Policies;

use App\Models\User;

class NoticePolicy
{
    /**
     * Determine if the user can view the notices module.
     * Only admins can access notices.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can create a notice.
     * Only admins can create notices.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update a notice.
     * Only admins can update notices.
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete a notice.
     * Only admins can delete notices.
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
