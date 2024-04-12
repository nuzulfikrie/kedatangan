<?php

namespace App\Policies;

use App\Models\Schoolsadmin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolsadminPolicy
{


    const ROLE_ADMIN = 'admin';
    const ROLE_SCHOOLADMIN = 'school_admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';
    const ROLE_PARENT = 'parent';

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasRole(self::ROLE_ADMIN)
            ? Response::allow()
            : Response::deny('You must be an admin to view Schools ladmin\.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        if ($user->role == self::ROLE_SCHOOLADMIN && $user->id == $Schoolsadmin->school_admin_id) {
            return Response::allow();
        }

        if ($user->hasRole(self::ROLE_ADMIN)) {
            return Response::allow();
        }

        return Response::deny('You must be an admin or school admin to view Schools ladmin\.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        // Only admin or school admin can create school admins
        return $user->hasRole(self::ROLE_ADMIN) || $user->hasRole(self::ROLE_SCHOOLADMIN)
            ? Response::allow()
            : Response::deny('You must be an admin or school admin to create Schools ladmin\.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        return $user->hasRole(self::ROLE_ADMIN) || $user->hasRole(self::ROLE_SCHOOLADMIN)
            ? Response::allow()
            : Response::deny('You must be an admin or school admin to edit Schools ladmin\.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        return $user->hasRole(self::ROLE_ADMIN)
            || ($user->hasRole(self::ROLE_SCHOOLADMIN) && $user->id !== $Schoolsadmin->school_admin_id)
            ? Response::allow()
            : Response::deny('You must be an admin or school admin to delete Schools ladmin\.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        // Add your logic here
        return $user->hasRole(self::ROLE_ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        return $user->hasRole(self::ROLE_ADMIN);
    }

    // add for index
    public function indexByUser(User $user, Schoolsadmin $Schoolsadmin): Response
    {
        dump('--- indexByUser ---');
        dump($user);
        dump($Schoolsadmin);

        dd($user->role);
        if ($user->role == self::ROLE_SCHOOLADMIN && $user->id == $Schoolsadmin->school_admin_id) {
            return Response::allow();
        }

        if ($user->hasRole(self::ROLE_ADMIN)) {
            return Response::allow();
        }

        return Response::deny('You must be an admin or school admin to view Schools ladmin\.');
    }
}
