<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateProfileRequest;
<<<<<<< Updated upstream
=======
use App\Models\Profile;
use App\Models\Customer;
use App\Models\Facility;
>>>>>>> Stashed changes
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer_id) {
            abort(403, 'No customer assigned.');
        }

        // Get latest profile for this customer (and facility if exists)
        $profileQuery = Profile::where('customer_id', $user->customer_id);

<<<<<<< Updated upstream
=======
        if ($user->facility_id) {
            $profileQuery->where('facility_id', $user->facility_id);
        } else {
            $profileQuery->whereNull('facility_id');
        }

        $profile = $profileQuery->orderByDesc('created_at')->first();

        // Get customer data for display
        $customer = Customer::findOrFail($user->customer_id);

        // If no profile exists, create a blank one (so the form doesn't break)
        if (!$profile) {
            $profile = new Profile([
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
        }

        return view('profile', compact('profile', 'customer'));
    }

>>>>>>> Stashed changes
    public function store(StoreProfileRequest $request)
    {
        $user = Auth::user();

        if (!$user->customer_id) {
            abort(403, 'No customer assigned.');
        }

        $customer = Customer::with('facilities')->findOrFail($user->customer_id);

        // Determine correct facility_id - ALWAYS from Auth user
        $facilityId = $user->facility_id ?? null;

        // Check if profile exists for this exact customer + facility combo
        $existingProfile = Profile::where('customer_id', $customer->id)
            ->where(function ($q) use ($facilityId) {
                if ($facilityId === null) {
                    $q->whereNull('facility_id');
                } else {
                    $q->where('facility_id', $facilityId);
                }
            })
            ->first();

        $data = $request->validated();
        $data['customer_id'] = $customer->id;
        $data['facility_id'] = $facilityId;

        if ($existingProfile) {
            // Update existing profile only
            $existingProfile->update($data);
        } else {
            // Create NEW profile with customer_id and facility_id
            Profile::create($data);
        }

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Profile saved successfully.');
    }

    public function edit()
    {
        $user = Auth::user();

        if (!$user->customer_id) {
            abort(403, 'No customer assigned.');
        }

        $profileQuery = Profile::where('customer_id', $user->customer_id);

        if ($user->facility_id) {
            $profileQuery->where('facility_id', $user->facility_id);
        } else {
            $profileQuery->whereNull('facility_id');
        }

        $profile = $profileQuery->orderByDesc('created_at')->first();

        // Get customer data for display
        $customer = Customer::findOrFail($user->customer_id);

<<<<<<< Updated upstream
        if (! $profile) {
            $profile = new Profile(['customer_id' => $customerId]);
=======
        if (!$profile) {
            $profile = new Profile([
                'customer_id' => $user->customer_id,
                'facility_id' => $user->facility_id ?? null,
            ]);
>>>>>>> Stashed changes
        }

        return view('edit-profile', compact('profile', 'customer'));
    }

   public function update(UpdateProfileRequest $request, $id)
{
    $user = Auth::user();

    if (!$user->customer_id) {
        abort(403, 'No customer assigned.');
    }

    $facilityId = $user->facility_id ?? null;

    $profile = Profile::where('id', $id)
        ->where('customer_id', $user->customer_id)
        ->where(function ($q) use ($facilityId) {
            if ($facilityId === null) {
                $q->whereNull('facility_id');
            } else {
                $q->where('facility_id', $facilityId);
            }
        })
        ->firstOrFail();

    $profile->update($request->validated());

    return redirect()->route('profiles.index')
        ->with('success', 'Profile updated successfully.');
}

    //Admin 
    public function profileList()
    {
<<<<<<< Updated upstream
        // Ensure the authenticated user’s customer_id matches the profile's customer_id
        if (Auth::user()->customer_id !== $profile->customer_id) {
            abort(403, 'You do not have permission to update this profile.');
        }

        // Update the profile with the validated request data
        $profile->update($request->validated());

        // Return back with a success message
        return redirect()->route('profiles.index')->with('success', 'Profile saved successfully.');
    }

    public function profileList()
    {
        $profiles = Profile::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.customer-profile.customer-profile-list', compact('profiles'));
    }

    public function createProfile(StoreProfileRequest $request)
    {
        $data = $request->validated();

        Profile::create($data);

        return redirect()
            ->route('admin.profiles.list')
            ->with('success', 'Profile created successfully.');
=======
        $profiles = Profile::with('customer', 'facility')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.customer-profile.customer-profile-list', compact('profiles'));
    }

    /**
     * AJAX: Get facilities for selected customer
     */
    public function getFacilitiesByCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        
        if (!$customerId) {
            return response()->json(['facilities' => []]);
        }

        $facilities = Facility::where('customer_id', $customerId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['facilities' => $facilities]);
    }

    /**
     * Show admin profile edit form
     */
    public function editProfileForm(Profile $profile)
    {
        $profile->load('customer', 'facility');
        $customers = Customer::with('facilities')->orderBy('account_name')->get();
        
        return view('admin.customer-profile.form-edit-customer-profile', compact('profile', 'customers'));
>>>>>>> Stashed changes
    }

    /**
     * Admin: update an existing profile.
     */
    public function updateProfile(AdminUpdateProfileRequest $request, Profile $profile)
    {
        $data = $request->validated();

        $profile->update([
<<<<<<< Updated upstream
            'customer_id' => $data['edit_customer_id'],
            'short_name' => $data['edit_short_name'],
            'account_name' => $data['edit_account_name'],
            'business_address' => $data['edit_business_address'],
            'facility_address' => $data['edit_facility_address'],
            'customer_category' => $data['edit_customer_category'],
            'cooperation_period_start_date' => $data['edit_cooperation_period_start_date'],
            'cooperation_period_end_date' => $data['edit_cooperation_period_end_date'],
            'contract_price' => $data['edit_contract_price'],
            'contracted_demand' => $data['edit_contracted_demand'],
            'certificate_of_contestability_number' => $data['edit_certificate_of_contestability_number'],
            'other_information' => $data['edit_other_information'],
            'contact_name' => $data['edit_contact_name'],
            'designation' => $data['edit_designation'],
            'mobile_number' => $data['edit_mobile_number'],
            'email' => $data['edit_email'],
            'contact_name_1' => $data['edit_contact_name_1'],
            'designation_1' => $data['edit_designation_1'],
            'mobile_number_1' => $data['edit_mobile_number_1'],
            'email_1' => $data['edit_email_1'],
=======
            'business_address' => $data['edit_business_address'] ?? $profile->business_address,
            'facility_address' => $data['edit_facility_address'] ?? $profile->facility_address,
            'customer_category' => $data['edit_customer_category'] ?? $profile->customer_category,
            'cooperation_period_start_date' => $data['edit_cooperation_period_start_date'] ?? $profile->cooperation_period_start_date,
            'cooperation_period_end_date' => $data['edit_cooperation_period_end_date'] ?? $profile->cooperation_period_end_date,
            'contract_price' => $data['edit_contract_price'] ?? $profile->contract_price,
            'contracted_demand' => $data['edit_contracted_demand'] ?? $profile->contracted_demand,
            'certificate_of_contestability_number' => $data['edit_certificate_of_contestability_number'] ?? $profile->certificate_of_contestability_number,
            'other_information' => $data['edit_other_information'] ?? $profile->other_information,
            'contact_name' => $data['edit_contact_name'] ?? $profile->contact_name,
            'designation' => $data['edit_designation'] ?? $profile->designation,
            'mobile_number' => $data['edit_mobile_number'] ?? $profile->mobile_number,
            'email' => $data['edit_email'] ?? $profile->email,
            'contact_name_1' => $data['edit_contact_name_1'] ?? $profile->contact_name_1,
            'designation_1' => $data['edit_designation_1'] ?? $profile->designation_1,
            'mobile_number_1' => $data['edit_mobile_number_1'] ?? $profile->mobile_number_1,
            'email_1' => $data['edit_email_1'] ?? $profile->email_1,
>>>>>>> Stashed changes
        ]);

        return redirect()
            ->route('admin.profiles.list')
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();
        
        return redirect()->route('admin.profiles.list')
            ->with('success', 'Profile deleted successfully.');
    }
}
