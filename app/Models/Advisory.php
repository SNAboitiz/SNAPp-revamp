<?php

namespace App\Models;

use App\Services\GcsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Advisory extends Model
{
    protected $fillable = [
        'headline',
        'description',
        'content',
        'attachment',
        'is_latest',
        'is_archive',
        'created_by',
        'link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $appends = ['attachment_url'];

    public function getAttachmentUrlAttribute()
    {
        if (! $this->attachment) {
            return null;
        }

        // Get the currently active default disk from config
        $defaultDisk = config('filesystems.default');

        if ($defaultDisk === 'gcs') {
            // If the active disk is GCS, use your custom GcsService to generate a signed URL
            // Lazily resolve GcsService from the container
            $gcsService = app(GcsService::class);

            return $gcsService->generateSignedUrl($this->attachment);

        } else {
            // For local or public disks, return the standard public URL
            return Storage::url($this->attachment);
        }
    }
}
