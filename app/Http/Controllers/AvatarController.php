<?php

namespace App\Http\Controllers;

use App\Traits\AvatarHandlerTrait;
use Illuminate\Http\Request;
use App\Models\File;

class AvatarController extends Controller
{

    use AvatarHandlerTrait;

    /**
     * Handle the avatar upload request.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $avatarPath = $this->uploadAvatar($request->file('avatar'));




        File::create([
            'storage_type' => 's3',
            'file_name' => $request->file('avatar')->getClientOriginalName(),
            'file_extension' => $request->file('avatar')->getClientOriginalExtension(),
            'file_path' =>  $avatarPath,
            'uploader_id' => $user->id,
            'remark' => 'avatar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['avatar_path' => $avatarPath], 201);
    }
}
