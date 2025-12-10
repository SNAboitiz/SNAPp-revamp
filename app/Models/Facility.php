<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sein',
        'customer_id',
    ];

    /**
     * Get the customer associated with the facility.
     *
     * @return BelongsTo<Customer>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the users associated with the facility.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the profiles associated with the facility.
     *
     * @return HasMany<Profile>
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Get the contracts associated with the facility.
     *
     * @return HasMany<Contract>
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the bills associated with the facility.
     *
     * @return HasMany<Bill>
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($facility) {
            // Auto-create blank profile for new facility
            Profile::create([
                'customer_id' => $facility->customer_id,
                'facility_id' => $facility->id,
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
