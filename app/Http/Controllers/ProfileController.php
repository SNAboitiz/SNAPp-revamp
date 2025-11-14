<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateProfileRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profiles = null;

        if ($user && $user->customer_id) {
            $profiles = Profile::where('customer_id', $user->customer_id)->first();
        }

        return view('profile', compact('profiles'));
    }

    public function store(StoreProfileRequest $request)
    {
        $customerId = Auth::user()->customer_id;

        // find or build a fresh one
        $profile = Profile::firstOrNew(['customer_id' => $customerId]);

        $profile->fill($request->validated());
        $profile->save();

        return redirect()->route('profiles.index')->with('success', 'Profile saved successfully.');
    }

    public function edit()
    {
        $customerId = Auth::user()->customer_id;
        $profile = Profile::where('customer_id', $customerId)->first();

        if (! $profile) {
            $profile = new Profile(['customer_id' => $customerId]);
        }

        return view('edit-profile', compact('profile'));
    }

    public function update(UpdateProfileRequest $request, Profile $profile)
    {
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
    }

    /**
     * Admin: update an existing profile.
     */
    public function updateProfile(AdminUpdateProfileRequest $request, Profile $profile)
    {
        $data = $request->validated();

        $profile->update([
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
        ]);

        return redirect()
            ->route('admin.profiles.list')
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(Profile $profile)
    {
        //
    }
}
