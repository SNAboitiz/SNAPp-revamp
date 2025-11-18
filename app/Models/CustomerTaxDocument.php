<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTaxDocument extends Model
{
    protected $fillable = [
        'customer_id',
        'facility_id',
        'document_number',
        'file_path',
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
