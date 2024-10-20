<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Glide\ServerFactory;
use League\Glide\Responses\PsrResponseFactory;
use Nyholm\Psr7\Factory\Psr17Factory;

trait AvatarHandlerTrait
{
    protected string $targetFileDimension = '300x300';

    /**
     * Upload and process the avatar.
     *
     * @param UploadedFile $file
     * @return string
     * @throws \Exception
     */
    public function uploadAvatar(UploadedFile $file): string
    {
        $filePath = $this->generateFilePath($file);
        $processedImage = $this->processImage($file);

        if (Storage::disk('s3')->put($filePath, $processedImage)) {
            return $filePath;
        }

        throw new \Exception('Failed to upload avatar to storage.');
    }

    /**
     * Process the image using native Glide.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function processImage(UploadedFile $file): string
    {
        $psr17Factory = new Psr17Factory();
        $responseFactory = new PsrResponseFactory(
            $psr17Factory->createResponse(),
            function () use ($psr17Factory) {
                return $psr17Factory->createResponse();
            }
        );

        $server = ServerFactory::create([
            'source' => dirname($file->getRealPath()),
            'cache' => storage_path('glide'), // Use Laravel's storage path for cache
            'driver' => extension_loaded('imagick') ? 'imagick' : 'gd',
            'response' => $responseFactory,
        ]);

        [$width, $height] = $this->parseDimensions($this->targetFileDimension);

        ob_start();
        $server->outputImage(basename($file->getRealPath()), [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
        ]);

        return ob_get_clean();
    }

    /**
     * Generate a unique file path for the uploaded avatar.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilePath(UploadedFile $file): string
    {
        $timestamp = time();
        $fileName = $timestamp . '_' . $file->getClientOriginalName();
        return "avatars/{$fileName}";
    }

    /**
     * Parse dimensions from a string format (e.g., '300x300').
     *
     * @param string $dimensions
     * @return array<int, int>
     * @throws \InvalidArgumentException
     */
    protected function parseDimensions(string $dimensions): array
    {
        $parts = explode('x', $dimensions);

        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            throw new \InvalidArgumentException('Invalid dimension format.');
        }

        return [(int) $parts[0], (int) $parts[1]];
    }

    /**
     * guide to use this trait 
     * 
     * 
     */
}
