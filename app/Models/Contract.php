<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'facility_id',

        'reference_number',
        'short_name',
        'description',
        'contract_start',
        'contract_end',
        'contract_period',
        'document',
        'status',
        'created_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
