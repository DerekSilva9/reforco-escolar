<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;

class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

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

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    public function update(User $user, Team $team): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isProfessor()) {
            return $team->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->isAdmin();
    }

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
}
