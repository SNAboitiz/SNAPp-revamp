<?php

namespace App\Models;

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

        return Storage::temporaryUrl($this->attachment, now()->addMinutes(30));
    }
}
