<?php

namespace App\Policies;

use App\Models\Schooladmins;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Laravel\Jetstream\Role;

class SchooladminsPolicy
{
    const  ROLE_ADMIN = 'admin';
    const  ROLE_SCHOOLADMIN = 'school_admin';
    const  ROLE_TEACHER = 'teacher';
    const  ROLE_STUDENT = 'student';
    const  ROLE_PARENT = 'parent';
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {


        $user->hasRole(self::ROLE_ADMIN) ?
            Response::allow() :
            Response::deny('You must be an admin to view schooladmins.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schooladmins $schooladmins): bool
    {
        //
        $user->hasRole(in_array(S)) ?
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schooladmins $schooladmins): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schooladmins $schooladmins): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schooladmins $schooladmins): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schooladmins $schooladmins): bool
    {
        //
    }
}
