<?php

namespace App\Policies;

use App\Models\Deployment;
use App\Models\User;

class DeploymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Deployment $deployment): bool
    {
        // Must load subdomain relation
        return $user->id === $deployment->subdomain->user_id || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Deployment $deployment): bool
    {
        return $user->id === $deployment->subdomain->user_id || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Deployment $deployment): bool
    {
        return $user->id === $deployment->subdomain->user_id || $user->role === 'Admin';
    }
}
