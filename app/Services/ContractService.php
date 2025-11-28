<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ContractService
{
    protected GcsService $gcsService;

    public function __construct(GcsService $gcsService)
    {
        $this->gcsService = $gcsService;
    }

    /**
     * Upload file to storage (GCS or local)
     */
    public function uploadFile(UploadedFile $file, string $filename): string
    {
        $disk = config('filesystems.default');
        return $file->storeAs('snapp_contracts', $filename, $disk);
    }

    /**
     * Get file URL from storage path
     */
    public function getFileUrl(string $path): ?string
    {
        $disk = config('filesystems.default');

        if ($disk === 'gcs') {
            // Use GCS service to generate signed URL
            $url = $this->gcsService->generateSignedUrl($path);
            return $url ?: null;
        }

        // For local storage, check if file exists and return URL
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }

        return null;
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists(string $path): bool
    {
        $disk = config('filesystems.default');
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(string $path): bool
    {
        $disk = config('filesystems.default');
        return Storage::disk($disk)->delete($path);
    }
}