<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'customer_id',
        'billing_start_date',
        'billing_end_date',
        'billing_period',
        'bill_number',
        'file_path',
        'uploaded_by',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'customer_id', 'customer_id');
    }
}
