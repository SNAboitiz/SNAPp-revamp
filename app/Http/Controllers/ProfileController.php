<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateProfileRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->cant('can view profile')) {
            abort(403, 'Unauthorized action.');
        }

        if (! $user || ! $user->customer_id) {
            abort(403, 'No customer assigned.');
        }

        // Get latest profile for this customer (and facility if exists)
        $profileQuery = Profile::where('customer_id', $user->customer_id);

        if ($user->facility_id) {
            $profileQuery->where('facility_id', $user->facility_id);
        } else {
            $profileQuery->whereNull('facility_id');
        }

        $profile = $profileQuery->orderByDesc('created_at')->first();

        // Get customer data for display
        $customer = Customer::findOrFail($user->customer_id);

        // If no profile exists, create a blank one (so the form doesn't break)
        if (! $profile) {
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

    public function store(StoreProfileRequest $request)
    {
        $user = Auth::user();

        if ($user->cant('can view profile')) {
            abort(403, 'Unauthorized action.');
        }

        if (! $user->customer_id) {
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

        if (! $user->customer_id) {
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

        if (! $profile) {
            $profile = new Profile([
                'customer_id' => $user->customer_id,
                'facility_id' => $user->facility_id ?? null,
            ]);
        }

        return view('edit-profile', compact('profile', 'customer'));
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        $user = Auth::user();

        if ($user->cant('can view profile')) {
            abort(403, 'Unauthorized action.');
        }

        if (! $user->customer_id) {
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

    // Admin
    public function profileList(Request $request)
    {
        $search = $request->search;

        $profiles = Profile::with('customer', 'facility')
            ->when($search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('account_name', 'like', "%{$search}%")
                        ->orWhere('customer_number', 'like', "%{$search}%")
                        ->orWhere('short_name', 'like', "%{$search}%");
                })->orWhereHas('facility', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sein', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        $customers = Customer::with('facilities')->orderBy('account_name')->get();

        return view('admin.customer-profile.customer-profile-list', compact('profiles', 'customers'));
    }

    /**
     * Show admin profile creation form with customer pre-selected (optional)
     */
    public function adminCreateProfileForm(Request $request)
    {
        $customerId = $request->input('customer_id');
        $customer = $customerId ? Customer::with('facilities')->findOrFail($customerId) : null;
        $customers = Customer::orderBy('account_name')->get();

        $profile = new Profile;

        return view('admin.customer-profile.create-profile', compact('profile', 'customer', 'customers'));
    }

    /**
     * AJAX: Get facilities for selected customer
     */
    public function getFacilitiesByCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');

        if (! $customerId) {
            return response()->json(['facilities' => []]);
        }

        $facilities = Facility::where('customer_id', $customerId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['facilities' => $facilities]);
    }

    public function createProfile(StoreProfileRequest $request)
    {
        $customerId = $request->input('customer_id');
        $facilityId = $request->input('facility_id');

        // Check for duplicate profile
        $existingProfile = Profile::where('customer_id', $customerId)
            ->where(function ($q) use ($facilityId) {
                if ($facilityId === null || $facilityId === '') {
                    $q->whereNull('facility_id');
                } else {
                    $q->where('facility_id', $facilityId);
                }
            })
            ->first();

        if ($existingProfile) {
            return redirect()->back()
                ->withErrors(['duplicate' => 'A profile already exists for this customer and facility combination.'])
                ->withInput();
        }

        $data = $request->validated();
        $data['customer_id'] = $customerId;
        $data['facility_id'] = $facilityId ?? null;

        $profile = Profile::create($data);
        $customer = Customer::findOrFail($customerId);
        $facilityName = $facilityId ? Facility::find($facilityId)?->name : 'No Facility';

        return redirect()
            ->route('admin.profiles.list')
            ->with('success', "Profile created successfully for {$customer->account_name} ({$facilityName})");
    }

    /**
     * Admin: update an existing profile.
     */
    public function updateProfile(AdminUpdateProfileRequest $request, Profile $profile)
    {
        $newCustomerId = $request->input('edit_customer_id') ?? $profile->customer_id;
        $newFacilityId = $request->input('edit_facility_id') ?? $profile->facility_id;

        // Check for duplicate only if customer or facility changed
        if ($newCustomerId != $profile->customer_id || $newFacilityId != $profile->facility_id) {
            $existingProfile = Profile::where('customer_id', $newCustomerId)
                ->where(function ($q) use ($newFacilityId) {
                    if ($newFacilityId === null || $newFacilityId === '') {
                        $q->whereNull('facility_id');
                    } else {
                        $q->where('facility_id', $newFacilityId);
                    }
                })
                ->where('id', '!=', $profile->id)
                ->first();

            if ($existingProfile) {
                return redirect()->back()
                    ->withErrors(['duplicate' => 'A profile already exists for this customer and facility combination.'])
                    ->with('show_modal', 'edit-customer-profile-modal')
                    ->withInput();
            }
        }

        $data = $request->validated();

        $profile->update([
            'customer_id' => $newCustomerId,
            'facility_id' => $newFacilityId ?? null,
            'business_address' => $data['edit_business_address'] ?? null,
            'facility_address' => $data['edit_facility_address'] ?? null,
            'customer_category' => $data['edit_customer_category'] ?? null,
            'cooperation_period_start_date' => $data['edit_cooperation_period_start_date'] ?? null,
            'cooperation_period_end_date' => $data['edit_cooperation_period_end_date'] ?? null,
            'contract_price' => $data['edit_contract_price'] ?? null,
            'contracted_demand' => $data['edit_contracted_demand'] ?? null,
            'certificate_of_contestability_number' => $data['edit_certificate_of_contestability_number'] ?? null,
            'other_information' => $data['edit_other_information'] ?? null,
            'contact_name' => $data['edit_contact_name'] ?? null,
            'designation' => $data['edit_designation'] ?? null,
            'mobile_number' => $data['edit_mobile_number'] ?? null,
            'email' => $data['edit_email'] ?? null,
            'contact_name_1' => $data['edit_contact_name_1'] ?? null,
            'designation_1' => $data['edit_designation_1'] ?? null,
            'mobile_number_1' => $data['edit_mobile_number_1'] ?? null,
            'email_1' => $data['edit_email_1'] ?? null,
        ]);

        return redirect()
            ->route('admin.profiles.list')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show admin profile edit form
     */
    public function editProfileForm(Profile $profile)
    {
        $profile->load('customer', 'facility');
        $customers = Customer::with('facilities')->orderBy('account_name')->get();

        return view('admin.customer-profile.edit-profile', compact('profile', 'customers'));
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();

        return redirect()->route('admin.profiles.list')
            ->with('success', 'Profile deleted successfully.');
    }
}
