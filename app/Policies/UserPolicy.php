<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only admin_rt and ketua_rt can view users list
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Only admin_rt and ketua_rt can view user details
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin_rt can create new users
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Only admin_rt can update users
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin_rt can delete users, and cannot delete themselves
        return $user->is_active && $user->isAdminRT() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only admin_rt can restore users
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admin_rt can force delete users, and cannot delete themselves
        return $user->is_active && $user->isAdminRT() && $user->id !== $model->id;
    }
}
