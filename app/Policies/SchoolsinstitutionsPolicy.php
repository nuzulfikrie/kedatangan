<?php

namespace App\Policies;

use App\Models\Schoolsinstitutions;
use App\Models\User;
use App\Models\Schoolsadmin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SchoolsinstitutionsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'school_admin' || $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        // Check if user is a school admin
        if ($user->role !== 'school_admin') {
            return false;
        }

        // Get all schools this admin has access to
        if ($user->role === 'school_admin') {
            $schoolAdminRecords = Schoolsadmin::where('school_admin_id', $user->id)
                ->pluck('school_id')
                ->toArray();

            $isAdminEligible = in_array($schoolsinstitutions->id, $schoolAdminRecords);

            return $isAdminEligible;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'school_admin' || $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        // Check if user is a school admin
        if ($user->role !== 'school_admin') {
            return false;
        }

        // Get all schools this admin has access to
        if ($user->role === 'school_admin') {
            $schoolAdminRecords = Schoolsadmin::where('school_admin_id', $user->id)
                ->pluck('school_id')
                ->toArray();

            $isAdminEligible = in_array($schoolsinstitutions->id, $schoolAdminRecords);

            return $isAdminEligible;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        // Check if user is a school admin
        if ($user->role !== 'school_admin') {
            return false;
        }

        // Get all schools this admin has access to
        if ($user->role === 'school_admin') {
            $schoolAdminRecords = Schoolsadmin::where('school_admin_id', $user->id)
                ->pluck('school_id')
                ->toArray();

            $isAdminEligible = in_array($schoolsinstitutions->id, $schoolAdminRecords);

            return $isAdminEligible;
        }
    }

    public function manage(User $user, Schoolsinstitutions $schoolsinstitutions): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        // Check if user is a school admin
        if ($user->role !== 'school_admin') {
            return false;
        }

        // Get all schools this admin has access to
        if ($user->role === 'school_admin') {
            $schoolAdminRecords = Schoolsadmin::where('school_admin_id', $user->id)
                ->pluck('school_id')
                ->toArray();

            $isAdminEligible = in_array($schoolsinstitutions->id, $schoolAdminRecords);

            return $isAdminEligible;
        }
    }
}
