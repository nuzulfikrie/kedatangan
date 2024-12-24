<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Schoolsinstitutions;
use Illuminate\Auth\Access\Response;

class SchoolsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->id === $school->school_admin_id) {
            return Response::allow(); // Allow access
        }

        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to view this school.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->id === $school->school_admin_id) {
            return Response::allow(); // Allow access
        }

        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to view this school.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        if ($user->role === 'school_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to create a school.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->role === 'school_admin') {
            return Response::allow(); // Allow access
        }

        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to view this school.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to delete this school.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to restore this school.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Schoolsinstitutions $school): Response
    {
        if ($user->role === 'super_admin') {
            return Response::allow(); // Allow access
        }

        return Response::deny('You are not authorized to force delete this school.');
    }
}
