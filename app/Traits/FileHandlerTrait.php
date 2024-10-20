<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait FileHandlerTrait
{
    /**
     * Upload a file to a specified storage type.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $storageType
     * @return string|false
     */
    public function uploadFile(UploadedFile $file, string $folder = 'uploads', string $storageType = 's3')
    {
        $path = $file->store($folder, $storageType);
        return $path ? $path : false;
    }

    /**
     * Delete a file from storage.
     *
     * @param string $filePath
     * @param string $storageType
     * @return bool
     */
    public function deleteFile(string $filePath, string $storageType = 's3'): bool
    {
        return Storage::disk($storageType)->delete($filePath);
    }

    /**
     * Check if a file exists in storage.
     *
     * @param string $filePath
     * @param string $storageType
     * @return bool
     */
    public function fileExists(string $filePath, string $storageType = 's3'): bool
    {
        return Storage::disk($storageType)->exists($filePath);
    }

    /**
     * Get the file URL from storage.
     *
     * @param string $filePath
     * @param string $storageType
     * @return string
     */
    public function getFileUrl(string $filePath, string $storageType = 's3'): string
    {
        return Storage::disk($storageType)->url($filePath);
    }
}
