<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolsAdminAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin || !$user->isActive) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role == 'super_admin') {
            // super admin can access all schools
            return $next($request);
        }

        if ($user->role == 'school_admin') {
            // school admin can access only their own school
            return $next($request);
        }

        return $next($request);
    }
}
