<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;

class AttendancePolicy
{
    /**
     * Determine if the user can view attendance records.
     * Admins and professors can view.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    /**
     * Determine if the user can view a specific team's attendance.
     * Only the professor who owns the team or admins can view.
     */
    public function view(User $user, Team $team): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProfessor()) {
            return $team->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can save/update attendance.
     * Only the professor who owns the team or admins can save.
     */
    public function save(User $user, Team $team): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProfessor()) {
            return $team->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create attendance records.
     * Admins and professors can create.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    /**
     * Determine if the user can update attendance records.
     * Admins and professors can update.
     */
    public function update(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }
}
