<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Schoolsinstitutions;
use App\Models\Schoolsadmin;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;


class SchoolsController extends Controller
{
    // in here we create, edit and delete schools

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Schoolsinstitutions::class, 'school');
        $this->authorizeResource(Schoolsinstitutions::class, 'create');
        $this->authorizeResource(Schoolsinstitutions::class, 'edit');
        $this->authorizeResource(Schoolsinstitutions::class, 'delete');
    }

    public function dashboard()
    {
        $this->authorize('dashboard', Schoolsinstitutions::class);

        $userId = auth()->user()->id;
        $user = User::find($userId);

        $schoolsAdmin = SchoolsAdmin::where('school_admin_id', '=', $userId)->firstOrFail();
        $hasSchool = SchoolsAdmin::isNotEmpty();

        return view('schools_admin.dashboard');
    }
    public function index(int $schoolAdminId)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);

            $schoolsAdmin = SchoolsAdmin::where('school_admin_id', '=', $userId)->firstOrFail();

            $this->authorize('view', $user, $schoolsAdmin);

            $hasSchool = SchoolsAdmin::isNotEmpty();
            dump('--- hasSchool ---');
            dump($hasSchool);


            if ($hasSchool) {
                $schoolIds = $schoolsAdmin->pluck('school_id');

                dump('--- schoolIds ---');
                dump($schoolIds);
                $schools = Schoolsinstitutions::whereIn('id', $schoolIds)->get();
            } else {
                $schools = null;
            }


            return view('schools_admin.schools.index', compact('schools', 'hasSchool'));
        } catch (Exception $e) {
            dd($e);
            $this->session()->flash('error', $e->getMessage());
            return redirect()->route('dashboard')->with(
                'error ' . $e->getMessage()

            );
        }
    }


    public function create()
    {
        //add policy
        $this->authorize('create', Schoolsinstitutions::class);
        return view('schools_admin.schools.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Schoolsinstitutions::class); // Use 'create' instead of 'store'
        try {

            $school = new Schoolsinstitutions();

            $school = $school->createRecords($request->all());
            if ($school) {
                //flash success message

                return redirect()
                    ->route(
                        'schools_admin.schools.index',
                        $request->school_admin_id
                    )
                    ->with('success', 'School created successfully');
            }

            return redirect()->route('schools_admin.schools.index', $request->school_admin_id);
        } catch (Exception $e) {
            // flash error message
            return redirect()->back()->with(
                'error',
                'Something went wrong, please try again later. Error '
                    . $e->getMessage()
                    . ' on '
                    . $e->getFile()
                    . ' at '
                    . $e->getLine()
            );
        }
    }


    public function edit(int $id)
    {
        try {
            $school = Schoolsinstitutions::findOrFail($id);
            return view('schools_admin.schools.edit', compact('school'));
        } catch (Exception $e) {

            return redirect()->back()->with(
                'error',
                'Error ' . $e->getMessage()
                    . ' on '
                    . $e->getFile()
                    . ' at '
                    . $e->getLine()
                    . 'Something went wrong, please try again later.'
            );
        }
    }

    public function show(int $id)
    {
        try {
            $school = Schoolsinstitutions::findOrFail($id);
            return view('schools_admin.schools.show', compact('school'));
        } catch (Exception $e) {

            return redirect()->back()->with(
                'error',
                'Error ' . $e->getMessage()
                    . ' on '
                    . $e->getFile()
                    . ' at '
                    . $e->getLine()
                    . 'Something went wrong, please try again later.'
            );
        }
    }

    public function update(Request $request)
    {
        try {
            $school = Schoolsinstitutions::find($request->id);
            $school->name = $request->name;
            $school->address = $request->address;
            $school->phone_number = $request->phone_number;
            $school->school_email = $request->school_email;
            $school->school_website = $request->school_website;
            $school->saveOrFail();
            return redirect()->route('schools_admin.schools.index', $request->school_admin_id);
        } catch (Exception $e) {

            return redirect()->back()->with(
                'error',
                'Error ' . $e->getMessage()
                    . ' on '
                    . $e->getFile()
                    . ' at '
                    . $e->getLine()
                    . 'Something went wrong, please try again later.'
            );
        }
    }

    public function delete(int $id)
    {
        try {

            //get current user id 
            $userId = auth()->user()->id;
            $user = User::find($userId);


            $school = Schoolsinstitutions::where('id', '=', $id)->firstOrFail();
            $schoolsAdmin = SchoolsAdmin::where('id', '=', $school->school_admin_id)->firstOrFail();

            $this->authorize('delete', $user, $schoolsAdmin); // Use 'delete' instead of 'destroy'

            $school->deleteOrFail();
            return redirect()->route('schools_admin.schools.index', $school->school_admin_id);
        } catch (Exception $e) {

            return redirect()->back()->with(
                'error',
                'Error ' . $e->getMessage()
                    . ' on '
                    . $e->getFile()
                    . ' at '
                    . $e->getLine()
                    . 'Something went wrong, please try again later.'
            );
        }
    }
}
