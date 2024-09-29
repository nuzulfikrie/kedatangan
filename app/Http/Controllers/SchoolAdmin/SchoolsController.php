<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\PivotClassChild;
use App\Models\Schoolsinstitutions;
use App\Models\Schoolsadmin;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolsController extends Controller
{
    // in here we create, edit and delete schools


    public function dashboard()
    {
        $this->authorize('dashboard', Schoolsinstitutions::class);

        $userId = auth()->user()->id;
        $user = User::find($userId);

        $schoolsAdmin = SchoolsAdmin::where('school_admin_id', '=', $userId)->firstOrFail();
        $hasSchool = SchoolsAdmin::isNotEmpty($userId);

        return view('schools_admin.dashboard');
    }
    public function index(int $schoolAdminId)
    {
        try {

            $role = auth()->user()->role;

            if ($role === 'school_admin') {
                $user = User::find($schoolAdminId);

                $schoolsAdmin = SchoolsAdmin::where('school_admin_id', '=', $schoolAdminId)->firstOrFail();


                $this->authorize('viewAny', Schoolsinstitutions::class);
                $hasSchool = SchoolsAdmin::isNotEmpty($schoolAdminId);



                if ($hasSchool) {
                    $schoolIds = $schoolsAdmin->pluck('school_id');
                    $schools = Schoolsinstitutions::whereIn('id', $schoolIds)->get();
                } else {
                    $schools = null;
                }
            } elseif ($role === 'admin') {
                # code...

                $schools = Schoolsinstitutions::all();
                $this->authorize('viewAny', Schoolsinstitutions::class);
            }




            return view('schools_admin.schools.index', compact('schools', 'hasSchool'));
        } catch (Exception $e) {
            Log::info('--- error --' . $e->getMessage());

            return redirect()->route('dashboard')->with(
                'error ',
                'Error ' .  $e->getMessage()

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




    public function show(Request $request, int $id)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $school = Schoolsinstitutions::findOrFail($id);
            if ($request->user()->cannot('view', $school)) {
                abort(403);
            }

            return view('schools_admin.schools.show', compact('school'));
        } catch (Exception $e) {
            Log::info('Error ');
            Log::info($e);
            return redirect()->route('dashboard')->with(
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


    public function edit(Request $request, int $id)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $school = Schoolsinstitutions::findOrFail($id);
            if ($request->user()->cannot('view', $school)) {
                abort(403);
            }

            return view('schools_admin.schools.edit', compact('school'));
        } catch (Exception $e) {
            Log::info('Error ');
            Log::info($e);
            return redirect()->route('dashboard')->with(
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
            $school = Schoolsinstitutions::findOrFail($request->id);
            if ($request->user()->cannot('update', $school)) {
                abort(403);
            }

            $school = Schoolsinstitutions::find($request->id);
            $school->name = $request->name;
            $school->address = $request->address;
            $school->phone_number = $request->phone_number;
            $school->school_email = $request->school_email;
            $school->school_website = $request->school_website;
            $school->saveOrFail();

            return redirect()->back()->withSuccess('Success Edit school')->route('schools_admin.schools.show', $school->id);
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


    public function delete(Request $request)
    {
        try {
            $id = $request->input('id');
            $role = $request->user()->role;
            $userId = $request->user()->id;

            // Find the school by ID
            $school = Schoolsinstitutions::findOrFail($id);

            // Check authorization
            if ($request->user()->cannot('delete', $school)) {
                abort(403);
            }

            DB::beginTransaction();

            // Delete related data
            $sadmin = Schoolsadmin::where('school_id', $school->id)->first();
            if ($sadmin) {
                $sadmin->delete();
            }

            $classes = Classes::where('school_id', $school->id)->get();
            foreach ($classes as $class) {
                $pivotClassChild = PivotClassChild::where('class_id', $class->id)->get();
                foreach ($pivotClassChild as $pivot) {
                    $pivot->delete();
                }
                $class->delete();
            }

            // Delete the school
            $school->deleteOrFail();

            DB::commit();

            $successMessage = 'Success delete school';
            if ($role === 'school_admin') {
                return redirect()->route('schools_admin.schools.index', $userId)->with('success', $successMessage);
            } elseif ($role === 'admin') {
                return redirect()->route('schools_admin.schools.index')->with('success', $successMessage);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting school: ' . $e->getMessage(), ['exception' => $e]);

            $errorMessage = 'Error ' . $e->getMessage() . ' on ' . $e->getFile() . ' at ' . $e->getLine() . ' Something went wrong, please try again later.';

            if ($role === 'school_admin') {
                return redirect()->route('schools_admin.schools.index', $userId)->withErrors($errorMessage);
            } elseif ($role === 'admin') {
                return redirect()->route('schools_admin.schools.index')->withErrors($errorMessage);
            }
        }
    }
}
