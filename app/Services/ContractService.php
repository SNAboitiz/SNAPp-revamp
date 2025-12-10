<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContractService
{
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
        return Storage::temporaryUrl($path, now()->addMinutes(30));
    }

    /**
     * Check if file exists in storage
     */
    public function fileExists(string $path): bool
    {
        return Storage::exists($path);
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(string $path): bool
    {
        return Storage::delete($path);
    }
}
