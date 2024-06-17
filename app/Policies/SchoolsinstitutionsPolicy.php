<?php

namespace App\Policies;

use App\Models\Schoolsadmin;
use App\Models\Schoolsinstitutions;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class SchoolsinstitutionsPolicy
{
    // Dashboard policy
    public function dashboard(User $user): bool
    {
        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id;
        }

        return $user->role === 'admin';
    }

    // Determine whether the user can view any models.
    public function viewAny(User $user): Response
    {

        if (in_array($user->role, ['school_admin', 'admin'])) {
            Log::info('--- role admin true');
            return Response::allow();
        }

        return Response::deny('You do not have permission to view schools institutions.');
    }

    // Determine whether the user can view the model.
    public function view(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {

        if ($user->role === 'school_admin') {

            $schoolAdmin = Schoolsadmin::where('school_id', $schoolsinstitutions->id)->first();
            return $user->id === $schoolAdmin->school_admin_id;
        }

        return $user->role === 'admin';
    }

    // Determine whether the user can create models.
    public function create(User $user): Response
    {
        if (in_array($user->role, ['admin', 'school_admin'])) {
            return Response::allow();
        }

        return Response::deny('You are not authorized to create a school.');
    }

    // Determine whether the user can update the model.
    public function update(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'school_admin') {

            $schoolAdmin = Schoolsadmin::where('school_id', $schoolsinstitutions->id)->first();
            return $user->id === $schoolAdmin->school_admin_id;
        }

        return $user->role === 'admin';
    }

    // Determine whether the user can delete the model.
    public function delete(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {

        if ($user->role === 'school_admin') {

            $schoolAdmin = Schoolsadmin::where('school_id', $schoolsinstitutions->id)->first();
            return $user->id === $schoolAdmin->school_admin_id;
        }

        return $user->role === 'admin';
    }

    // Determine whether the user can restore the model.
    public function restore(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'school_admin') {
            return $user->id === $user->school_admin_id && $schoolsinstitutions->trashed();
        }

        return $user->role === 'admin';
    }

    // Determine whether the user can permanently delete the model.
    public function forceDelete(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        return $user->role === 'admin' && $schoolsinstitutions->trashed();
    }
}
