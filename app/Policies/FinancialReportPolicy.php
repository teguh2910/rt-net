<?php

namespace App\Policies;

use App\Models\FinancialReport;
use App\Models\User;

class FinancialReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view financial reports
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FinancialReport $financialReport): bool
    {
        // All authenticated users can view report details
        return $user->is_active;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin, Ketua, and Bendahara can create reports
        return $user->is_active && $user->canManageFinances();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FinancialReport $financialReport): bool
    {
        // Admin, Ketua, and Bendahara can update reports
        return $user->is_active && $user->canManageFinances();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FinancialReport $financialReport): bool
    {
        // Only admin_rt and ketua_rt can delete reports
        return $user->is_active && $user->canManage();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FinancialReport $financialReport): bool
    {
        // Only admin_rt can restore reports
        return $user->is_active && $user->isAdminRT();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FinancialReport $financialReport): bool
    {
        // Only admin_rt can force delete reports
        return $user->is_active && $user->isAdminRT();
    }
}
