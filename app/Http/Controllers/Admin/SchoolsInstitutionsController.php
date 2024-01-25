<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schoolsinstitutions;
use App\Models\Childs;
use App\Models\ChildParents;

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

    // view a school using id parameter
    public function view($id)
    {
        //get school by id
        $school = Schoolsinstitutions::find($id);
        //return view with school
        return view('admin.schools_institutions.view', compact('school'));
    }

    //edit a school using id parameter
    public function edit($id)
    {
        //get school by id
        $school = Schoolsinstitutions::find($id);
        //return view with school
        return view('admin.schools_institutions.edit', compact('school'));
    }

    //delete a school along with its related records using id parameter
    public function delete($id)
    {
        //get children by school id
        $children = Children::where('school_id', $id)->get();

        //get school by id
        $school = Schoolsinstitutions::find($id);
        //delete school
        $school->delete();
        //return to schools list
        return redirect()->route('admin.schools_institutions.index');
    }
}
