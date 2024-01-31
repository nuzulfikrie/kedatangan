<?php

namespace App\Policies;

use App\Models\Schoolsinstitutions;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolsinstitutionsPolicy
{

    //dashboard
    public function dashboard(User $user): bool
    {
        // 1 - check if user role is school admin or admin
        // 2 - if user role is school admin, check if user id is the same as school admin id
        // 3 - if user role is admin, return true

        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }

        return false;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // 1 - check if user role is school admin or admin
        // 2 - if user role is school admin, check if user id is the same as school admin id
        // 3 - if user role is admin, return true

        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user): bool
    {

        // if (in_array($user->role, ['admin', 'school_admin'], true)) {
        //     return true;
        // } else {
        //     return false;
        // }

        dump('--- index ---');

        dd($user->role);
        return in_array($user->role, ['admin', 'school_admin'], true)
            ? true
            : false;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        //1 - check if user role is school admin or admin
        //2 - if user role is school admin, check if user id is the same as school admin id
        // the id is in the url
        //3 - if user role is admin, return true
        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {

        // policy for creating a school
        // only admin or school_admin can create a school
        // if user role is admin, return true
        if (in_array($user->role, ['admin', 'school_admin'])) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to create a school');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        //check if request is from school admin or admin and if the school is existing
        if ($user->role === 'school_admin' && !$schoolsinstitutions->trashed()) {
            return $user->id === $user->school_admin_id
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        //1 - check if user role is school admin or admin and if the school is not deleted
        if ($user->role === 'school_admin' && !$schoolsinstitutions->trashed()) {
            return $user->id === $user->school_admin_id
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id && $schoolsinstitutions->trashed()
                ? true
                : false;
        } elseif ($user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'admin' && $schoolsinstitutions->trashed()) {
            return true;
        }

        return false;
    }
}
