<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'account_name',
        'customer_number',
        'short_name',
    ];

    /**
     * Get the facilities associated with the customer.
     *
     * @return HasMany<Facility>
     */
    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    /**
     * Get the users associated with the customer.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customerTaxDocuments(): HasMany
    {
        return $this->hasMany(CustomerTaxDocument::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($customer) {
            // Auto-create blank profile for new customer
            Profile::create([
                'customer_id' => $customer->id,
                'facility_id' => null,
                'business_address' => '',
                'facility_address' => '',
                'customer_category' => '',
                'cooperation_period_start_date' => null,
                'cooperation_period_end_date' => null,
                'contract_price' => '',
                'contracted_demand' => '',
                'certificate_of_contestability_number' => '',
                'other_information' => '',
                'contact_name' => '',
                'designation' => '',
                'email' => '',
                'mobile_number' => '',
                'contact_name_1' => '',
                'designation_1' => '',
                'email_1' => '',
                'mobile_number_1' => '',
            ]);
        });
    }
}
