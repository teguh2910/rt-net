<?php

namespace App\Policies;

use App\Models\DigitalLetter;
use App\Models\User;

class DigitalLetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view digital letters
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DigitalLetter $digitalLetter): bool
    {
        // Warga can only view their own letters
        if ($user->isWarga() && $user->resident) {
            return $user->resident->id === $digitalLetter->resident_id;
        }

        // Admin and other roles can view all letter details
        return $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin_rt and ketua_rt can create letters
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DigitalLetter $digitalLetter): bool
    {
        // Only admin_rt and ketua_rt can update letters
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DigitalLetter $digitalLetter): bool
    {
        // Only admin_rt can delete letters
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DigitalLetter $digitalLetter): bool
    {
        // Only admin_rt can restore letters
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DigitalLetter $digitalLetter): bool
    {
        // Only admin_rt can force delete letters
        return $user->is_active && $user->isAdminRT();
    }
}
