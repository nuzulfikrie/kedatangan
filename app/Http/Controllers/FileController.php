<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $file = $request->file('file');
        $fileModel = new File();

        $path = $fileModel->uploadFile($file);

        if ($path) {
            $fileModel->fill([
                'storage_type' => 's3',
                'file_name' => $file->getClientOriginalName(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_path' => $path,
                'uploader_id' => auth()->id(),
            ])->save();

            return response()->json(['message' => 'File uploaded successfully!'], 201);
        }

        return response()->json(['error' => 'File upload failed!'], 500);
    }
}
