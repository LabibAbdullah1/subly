<?php

namespace App\Policies;

use App\Models\Subdomain;
use App\Models\User;

class SubdomainPolicy
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
    public function view(User $user, Subdomain $subdomain): bool
    {
        return $user->id === $subdomain->user_id || $user->role === 'Admin';
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
    public function update(User $user, Subdomain $subdomain): bool
    {
        return $user->id === $subdomain->user_id || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subdomain $subdomain): bool
    {
        return $user->id === $subdomain->user_id || $user->role === 'Admin';
    }
}
