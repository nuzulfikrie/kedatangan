<?php

namespace App\Http\Controllers;

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
            $schools = auth()->user()->schools;

            if ($hasSchools) {
                $students = $schools->students ? $schools->students : null;
            } else {
                $students = null;
            }

            $data = [
                'hasSchools' => $hasSchools,
                'students' => $students,
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
