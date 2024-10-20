<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Parents as ParentModel;

class ParentsPolicy
{
    /**
     * Determine if the given user can view any parent profiles.
     * - to use this in a controller 
     * 
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can view a specific parent profile.
     *
     * @param  \App\Models\User  $user
     * 
     * @return bool
     */
    public function view(User $user, ParentModel $parent): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can create a parent profile.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can update the parent profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parent  $parent
     * @return bool
     */
    public function update(User $user, ParentModel $parent): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can delete the parent profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parent  $parent
     * @return bool
     */
    public function delete(User $user, ParentModel $parent): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can restore the parent profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parent  $parent
     * @return bool
     */
    public function restore(User $user, ParentModel $parent): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Determine if the given user can force delete the parent profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parent  $parent
     * @return bool
     */
    public function forceDelete(User $user, ParentModel $parent): bool
    {
        return $this->hasAccess($user);
    }

    /**
     * Helper function to determine if the user has the necessary role.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function hasAccess(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'father', 'mother']);
    }
}
