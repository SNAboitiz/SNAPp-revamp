<?php

namespace App\Models;

use App\Observers\InquiryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(InquiryObserver::class)]
class Inquiry extends Model
{
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
        // TODO: save id from kissflow
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
}
