<?php

namespace App\Policies;

use App\Models\Finance;
use App\Models\User;

class FinancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view finance list
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Finance $finance): bool
    {
        // All authenticated users can view finance details
        return $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin, Ketua, and Bendahara can create finance records
        return $user->is_active && $user->canManageFinances();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Finance $finance): bool
    {
        // Admin, Ketua, and Bendahara can update finance records
        return $user->is_active && $user->canManageFinances();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Finance $finance): bool
    {
        // Only admin_rt and ketua_rt can delete finance records
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Finance $finance): bool
    {
        // Only admin_rt can restore finance records
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Finance $finance): bool
    {
        // Only admin_rt can force delete finance records
        return $user->is_active && $user->isAdminRT();
    }
}
