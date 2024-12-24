<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\PivotClassChild;
use App\Models\Schoolsinstitutions;
use App\Models\Schoolsadmin;
use App\Models\Childs;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolsinstitutionsController extends Controller
{
    // in here we create, edit and delete schools


    public function dashboard()
    {
        $this->authorize('dashboard', Schoolsinstitutions::class);



        return view('schools_admin.dashboard');
    }
    public function index(Request $request)
    {
        try {
            $query = $request->get('query');
            $filter = $request->get('filter', 'all');

            $role = auth()->user()->role;
            $userId = auth()->user()->id;

            // Initialize schools query
            $schools = null;
            $hasSchool = false;

            if ($role === 'school_admin') {
                $schoolsAdmin = SchoolsAdmin::where('school_admin_id', $userId)->get();
                $schoolIds = $schoolsAdmin->pluck('school_id');

                $this->authorize('viewAny', Schoolsinstitutions::class);
                $hasSchool = SchoolsAdmin::isNotEmpty($userId);

                if ($hasSchool) {
                    $schools = Schoolsinstitutions::whereIn('id', $schoolIds);
                }
            } elseif ($role === 'super_admin') {
                $schools = Schoolsinstitutions::query();
                $hasSchool = true;
                $this->authorize('viewAny', Schoolsinstitutions::class);
            }

            if ($schools) {
                // Apply search
                if ($query) {
                    $schools->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('address', 'LIKE', "%{$query}%")
                            ->orWhere('school_email', 'LIKE', "%{$query}%");
                    });
                }

                // Apply filters
                if ($filter === 'active') {
                    $schools->where('record_active', true);
                } elseif ($filter === 'inactive') {
                    $schools->where('record_active', false);
                }

                $schools = $schools->paginate(20)->withQueryString();
            } else {
                // If no schools query was initialized, create an empty paginator
                $schools = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array of items
                    0,  // Total items
                    20, // Items per page
                    1   // Current page
                );
            }

            return view('schools_admin.schools.index', compact('schools', 'hasSchool', 'query', 'filter'));
        } catch (Exception $e) {
            Log::info('--- error --' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error ' . $e->getMessage());
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




    public function show(Request $request, Schoolsinstitutions $schoolsinstitutions)
    {
        try {
            // Use authorize method from AuthorizesRequests trait
            $school = Schoolsinstitutions::findOrFail($schoolsinstitutions->id);
            $this->authorize('view', [$school]);

            return view('schools_admin.schools.show', [
                'school' => $schoolsinstitutions
            ]);
        } catch (Exception $e) {
            Log::error('Error viewing school: ' . $e->getMessage());
            return redirect()->route('dashboard')->with(
                'error',
                'Unable to view school. Please try again later.'
            );
        }
    }


    public function edit(Request $request, int $id)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $school = Schoolsinstitutions::findOrFail($id);
            $this->authorize('update', $school);

            if ($request->user()->cannot('update', [$user, $school])) {
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
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $school = Schoolsinstitutions::findOrFail($request->id);
            if ($request->user()->cannot('update', [$user, $school])) {
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
            $user = User::find($userId);
            // Find the school by ID
            $school = Schoolsinstitutions::findOrFail($id);

            // Check authorization
            if ($request->user()->cannot('delete', [$user, $school])) {
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

    public function manage(int $id)
    {

        $school = Schoolsinstitutions::findOrFail($id);
        //find all childs for this school
        $childs = Childs::where('school_id', $school->id)->get();

        $this->authorize('manage', [$school]);
        return view('schools_admin.schools.manage', compact('school', 'childs'));
    }
}
