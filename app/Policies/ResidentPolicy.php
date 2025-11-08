<?php

namespace App\Policies;

use App\Models\Resident;
use App\Models\User;

class ResidentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view residents list
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Resident $resident): bool
    {
        // Warga can only view their own data
        if ($user->isWarga() && $user->resident) {
            return $user->resident->id === $resident->id;
        }

        // Admin and other roles can view all resident details
        return $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin_rt and ketua_rt can create residents
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Resident $resident): bool
    {
        // Only admin_rt and ketua_rt can update residents
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Resident $resident): bool
    {
        // Only admin_rt can delete residents
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Resident $resident): bool
    {
        // Only admin_rt can restore residents
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Resident $resident): bool
    {
        // Only admin_rt can force delete residents
        return $user->is_active && $user->isAdminRT();
    }
}
