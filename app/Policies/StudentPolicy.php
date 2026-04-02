<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isProfessor()) {
            return $student->team?->user_id === $user->id;
        }
        
        if ($user->isResponsavel()) {
            return $student->responsavel_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    public function update(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isProfessor()) {
            return $student->team?->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    public function export(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }
}
