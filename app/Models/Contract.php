<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'reference_number',
        'shortname',
        'description',
        'contract_start',
        'contract_end',
        'contract_period',
        'document',
        'status',
        'created_by',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
