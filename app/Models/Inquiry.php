<?php

namespace App\Models;

use App\Observers\InquiryObserver;
use Filament\Forms\Components\RichEditor\FileAttachmentProviders\SpatieMediaLibraryFileAttachmentProvider;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(InquiryObserver::class)]
class Inquiry extends Model implements HasRichContent
{
    use InteractsWithRichContent;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'type',
        'message',
        'kissflow_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = ['user'];

    /**
     * Get the user that owns the inquiry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setUpRichContent(): void
    {
        $this->registerRichContent('content')
            ->fileAttachmentProvider(SpatieMediaLibraryFileAttachmentProvider::class)
            ->fileAttachmentsDisk(config('filesystems.default'))
            ->fileAttachmentsVisibility('private');
    }
}
