<?php

namespace App\Policies;

use App\Models\User;

class FinancePolicy
{
    /**
     * Determine if the user can view the finance module (list all payments).
     * Only admins can access finance reports.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can view a specific payment.
     * Only admins can view individual payments.
     */
    public function view(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can create a payment.
     * Only admins can create payments.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update a payment.
     * Only admins can update payments.
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete a payment.
     * Only admins can delete payments.
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
