<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the customer associated with the bill.
     *
     * @return BelongsTo<Customer>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the facility associated with the bill.
     *
     * @return BelongsTo<Facility>
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
