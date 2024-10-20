<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateParentRequest;
use App\Traits\AvatarHandlerTrait;
use App\Models\Childs;
use App\Models\ChildParents;
use App\Models\Parents;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Faker\Factory as Faker;


class ParentsController extends Controller
{
    use AvatarHandlerTrait;
    protected $faker;
    public function index()
    {
        $this->authorize('viewAny', Parents::class);

        $parents = Parents::with('user', 'childs')->paginate(15);
        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        $this->authorize('create', Parents::class);

        return view('parents.create');
    }

    public function store(CreateParentRequest $request)
    {

        try {
            $this->authorize('create', Parents::class);

            $validated = $request->validated();

            $user = $request->user();

            // check if has file 'picture'
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');

                $avatarPath = $this->uploadAvatar($file);


                File::create([
                    'storage_type' => 's3',
                    'file_name' => $request->file('picture')->getClientOriginalName(),
                    'file_extension' => $request->file('picture')->getClientOriginalExtension(),
                    'file_path' =>  $avatarPath,
                    'uploader_id' => $user->id,
                    'remark' => 'avatar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->faker = Faker::create('ms_MY');
                $avatarPath = $this->faker->imageUrl(640, 480, 'people', true, 'avatar', true, 'jpg');
            }

            $parent = Parents::create([
                'user_id' => $validated['user_id'],
                'parent_name' => $validated['parent_name'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
                'picture_path' => $avatarPath,
            ]);

            return redirect()->route('parents.profile', $parent->id)->with('success', 'Parent created successfully.');
        } catch (Exception $e) {
            //throw $th;

            Log::info('Error occurred in ' . $e->getFile() . ' at line ' . $e->getLine() . ' message  ' . $e->getMessage());

            return redirect()->route('parents.create')->with('error', 'An error occurred while creating the parent. Please try again later.');
        }
    }

    public function profile(int $parentId)
    {
        $parent = Parents::where('id', $parentId)->with(
            [
                'user',
                'childs'
            ]
        )->first();
        $this->authorize('view', $parent);

        $user = $parent->user;

        return view('parents.profile', compact('parent', 'user'));
    }

    public function show(Parents $parent)
    {
        $this->authorize('view', $parent);

        $parent->load('user', 'childs.school');
        return view('parents.show', compact('parent'));
    }

    public function edit(int $parentId)
    {
        $parent = Parents::findOrFail($parentId);
        $this->authorize('update', $parent);

        return view('parents.edit', compact('parent'));
    }
    public function update(Request $request, Parents $parent)
    {
        $this->authorize('update', $parent);
        $user = $request->user();
        $validated = $request->validate([
            'parent_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $parent->user_id,
            'phone_number' => 'required|string|max:15',
            'picture_path' => 'nullable|string',
            'race' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($validated, $parent, $request, $user) {
            $parent->user->update([
                'name' => $validated['parent_name'],
                'email' => $validated['email'],
            ]);

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');

                $avatarPath = $this->uploadAvatar($file);


                File::create([
                    'storage_type' => 's3',
                    'file_name' => $request->file('picture')->getClientOriginalName(),
                    'file_extension' => $request->file('picture')->getClientOriginalExtension(),
                    'file_path' =>  $avatarPath,
                    'uploader_id' => $user->id,
                    'remark' => 'avatar',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $parent->picture_path = $avatarPath;
            }

            $parent->parent_name = $validated['parent_name'];
            $parent->email = $validated['email'];
            $parent->phone_number = $validated['phone_number'];
            $parent->race = $validated['race'];
            $parent->save();


            $parent->update($validated);
        });

        return redirect()->route('parents.profile', $parent->id)->with('success', 'Parent updated successfully.');
    }

    public function destroy(Parents $parent)
    {
        $this->authorize('delete', $parent);

        $result = Parents::deleteParent($parent->id);

        if ($result['code'] === 200) {
            return redirect()->route('parents.index')->with('success', $result['message']);
        } else {
            return redirect()->route('parents.index')->with('error', $result['message']);
        }
    }

    public function addChild(Request $request, Parents $parent)
    {
        $this->authorize('update', $parent);

        $validated = $request->validate([
            'child_id' => 'required|exists:childs,id',
        ]);

        ChildParents::create([
            'child_id' => $validated['child_id'],
            'parent_id' => $parent->id,
        ]);

        return redirect()->route('parents.show', $parent)->with('success', 'Child added successfully.');
    }

    public function manageYourChilds(int $parentId)
    {

        $parent = Parents::findOrFail($parentId);

        $this->authorize('view', $parent);

        $children = $parent->childs()->with('school')
            ->with('childrenData')
            ->get();

        return view('parents.manage_your_childs', compact('parent', 'children'));
    }


    public function removeChild(Parents $parent, Childs $child)
    {
        $this->authorize('update', $parent);

        ChildParents::where('parent_id', $parent->id)
            ->where('child_id', $child->id)
            ->delete();

        return redirect()->route('parents.show', $parent)->with('success', 'Child removed successfully.');
    }

    public function childrenAttendance(Parents $parent)
    {
        $this->authorize('view', $parent);

        $children = $parent->childs()->with([
            'attendance' => function ($query) {
                $query->orderBy('date', 'desc')->limit(10);
            },
            'nonattendace' => function ($query) {
                $query->orderBy('date', 'desc')->limit(10);
            },
            'unknowns' => function ($query) {
                $query->orderBy('date', 'desc')->limit(10);
            }
        ])->get();

        return view('parents.children_attendance', compact('parent', 'children'));
    }
}
