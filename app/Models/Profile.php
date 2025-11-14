<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'short_name',
        'business_address',
        'facility_address',
        'customer_category',
        'cooperation_period_start_date',
        'cooperation_period_end_date',
        'contract_price',
        'contracted_demand',
        'other_information',
        'contact_name',
        'designation',
        'customer_id',
        'email',
        'mobile_number',

        // New secondary contact fields
        'contact_name_1',
        'designation_1',
        'mobile_number_1',
        'email_1',
        'account_executive',
        'certificate_of_contestability_number',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'customer_id', 'customer_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'customer_id', 'customer_id');
    }
}
