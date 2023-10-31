<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schoolsinstitutions;

class SchoolsInstitutionsController extends Controller
{
    //index function list all schools
    public function index()
    {
        //get all schools
        $schools = Schoolsinstitutions::all();
        //return view with schools
        return view('admin.schools_institutions.index', compact('schools'));
    }
}
