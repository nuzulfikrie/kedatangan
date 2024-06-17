<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\PivotClassChild;
use Illuminate\Http\Request;
use LDAP\Result;

class DashboardController extends Controller
{
    public function index()
    {

        $role = auth()->user()->role;
        if ($role == 'admin') {
            return view('admin.dashboard');
        } elseif ($role == 'parent') {
            return view('parent.dashboard');
        } elseif ($role == 'teacher') {
            return view('teacher.dashboard');
        } elseif ($role == 'school_admin') {


            $hasSchools = auth()->user()->schools ? true : false;
            $schools = auth()->user()->schools->pluck('id');

            if ($hasSchools) {
                $classes = Classes::whereIn('school_id', $schools)->get();
            } else {
                $classes = null;
            }

            $data = [
                'hasSchools' => $hasSchools,
                'classes' => $classes,
                'schools' => $schools
            ];
            //set template use
            //redirect to school admin dashboard
            return view('schools_admin.dashboard', $data)->with('success', 'you are viewing the school admin dashboard');
        } else {
            return view('admin.dashboard');
        }
    }
}
