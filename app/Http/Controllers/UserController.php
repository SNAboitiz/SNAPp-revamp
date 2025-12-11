<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCustomerRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\StoreAccountExecutive;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateAERequest;
use App\Mail\CustomerPasswordMail;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\Scopes\HasActiveScope;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // ======================================================================
    // CUSTOMER ACCOUNT MANAGEMENT
    // ======================================================================

    public function index(Request $request)
    {
        $query = User::query()->role('customer')->with(['customer', 'facility']);
        $users = $this->applyCommonFiltersAndPagination(
            $query,
            $request,
            ['active', 'search', 'sort']
        );

        $customers = Customer::with('facilities')->orderBy('account_name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.customer-account.customer-list', compact(
            'users',
            'customers',
            'facilities'
        ));
    }

    public function store(StoreCustomerRequest $request)
    {
        return $this->createUserWithRole(
            $request,
            'customer',
            'Customer account created successfully.',
            'Failed to create customer account.'
        );
    }

    /**
     * Update the specified customer and their customer in the database.
     * Note: This logic is unique to customers and is not refactored.
     */
    public function update(EditCustomerRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['edit_name'],
            'email' => $validated['edit_email'],
            'customer_id' => $validated['edit_customer_id'] ?? null,
            'facility_id' => $validated['edit_facility_id'] ?? null,
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.customer-account.form-edit-customer', compact('user'));
    }

    /**
     * Remove the specified user from the database.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['success' => true]);
    }

    // ======================================================================
    // ADMIN ACCOUNT MANAGEMENT
    // ======================================================================

    public function showAdmins(Request $request)
    {
        $query = User::role('admin')->with('customer');

        $admins = $this->applyCommonFiltersAndPagination(
            $query,
            $request,
            ['active', 'search', 'sort']
        );
        $customers = Customer::with('facilities')->orderBy('account_name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.admin-account.admin-list', compact('admins', 'customers', 'facilities'));
    }

    public function storeAdmins(StoreAdminRequest $request)
    {
        return $this->createUserWithRole(
            $request,
            'admin',
            'Admin account created successfully.',
            'Failed to create admin account.'
        );
    }

    public function updateAdmins(UpdateAdminRequest $request, User $user)
    {
        return $this->updateUserSimple(
            $request,
            $user,
            'admin-list',
            'Admin updated successfully.'
        );
    }

    // ======================================================================
    // ACCOUNT EXECUTIVE (AE) MANAGEMENT
    // ======================================================================

    public function showAE(Request $request)
    {
        $query = User::query()->role('account executive')->with('customer', 'facility');

        $accountExecutives = $this->applyCommonFiltersAndPagination(
            $query,
            $request,
            ['active', 'search', 'sort']
        );
        $customers = Customer::orderBy('account_name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.account-executive.account-executive-list', compact('accountExecutives', 'customers', 'facilities'));
    }

    public function storeAE(StoreAccountExecutive $request)
    {
        return $this->createUserWithRole(
            $request,
            'account executive',
            'Account Executive created successfully.',
            'Failed to create Account Executive.'
        );
    }

    public function updateAE(UpdateAERequest $request, User $user)
    {
        return $this->updateUserSimple(
            $request,
            $user,
            'account-executive-list', // Assumed route name, change if different
            'Account Executive updated successfully.'
        );

        return redirect()->route('account-executive-list')->with('success', 'Account Executive updated successfully.');
    }

    // ======================================================================
    // ALL USERS MANAGEMENT
    // ======================================================================

    public function showAllUsers(Request $request)
    {
        $query = User::query()->withoutGlobalScope(HasActiveScope::class);

        // Apply specific filter for the 'role' before calling the common helper
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $allUsers = $this->applyCommonFiltersAndPagination(
            $query,
            $request,
            ['role', 'active', 'search', 'sort']
        );
        $customers = Customer::orderBy('account_name')->get();
        $facilities = Facility::orderBy('name')->get();

        $roles = Role::all();

        return view('admin.all-users.all-users-list', compact('allUsers', 'roles', 'customers', 'facilities'));
    }

    /**
     * Update a user's status and role from the "All Users" list.
     * Note: This logic is unique and is not refactored.
     */
    public function editAllUsers(EditUserRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // Bypass global scope so even inactive users can be updated
            $user = User::withoutGlobalScope(HasActiveScope::class)->findOrFail($id);

            // Update the user's details from the validated request
            $validated = $request->validated();
            $user->fill($validated);

            // Update active status from the hidden input in your modal
            $user->facility_id = $request->input('facility_id', null);

            $user->active = (int) $request->input('active', $user->active);

            $user->save();

            // Update role if provided
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
            }

            // Check if the "Resend welcome email" box was ticked in the modal form
            if ($request->boolean('resend_welcome_email')) {
                $this->sendNewPassword($user);
                $successMessage = 'User updated and a new password has been sent.';
            } else {
                $successMessage = 'User details updated successfully.';
            }

            DB::commit();

            return redirect()->route('all-user-list')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('All Users Edit Failed for user '.$id.': '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to update user details.');
        }
    }

    // ======================================================================
    // PRIVATE REUSABLE HELPER METHODS
    // ======================================================================

    private function applyCommonFiltersAndPagination(Builder $query, Request $request, array $appendedParams)
    {
        // Apply active filter
        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->input('sort', 'created_at_desc');
        $sortMap = [
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'created_at_asc' => ['created_at', 'asc'],
            'created_at_desc' => ['created_at', 'desc'],
        ];
        [$column, $direction] = $sortMap[$sort] ?? $sortMap['created_at_desc'];
        $query->orderBy($column, $direction);

        // Paginate and append query parameters
        return $query->paginate(10)->appends($request->only($appendedParams));
    }

    private function createUserWithRole(FormRequest $request, string $role, string $successMessage, string $errorMessage)
    {
        DB::beginTransaction();
        try {
            $password = Str::random(8);
            $validated = $request->validated();

            $user = User::create($validated + ['password' => bcrypt($password)]);
            $user->assignRole($role);

            Mail::to($user->email)->send(new CustomerPasswordMail($password));

            DB::commit();

            return redirect()->back()->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(ucfirst($role).' Creation Failed: '.$e->getMessage());

            return redirect()->back()->with('error', $errorMessage);
        }
    }

    private function updateUserSimple(FormRequest $request, User $user, string $redirectRoute, string $successMessage)
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['edit_name'],
            'email' => $validated['edit_email'],
            'customer_id' => $validated['edit_customer_id'] ?? null,
        ]);

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    public function resetPassword(User $user)
    {
        try {
            $this->sendNewPassword($user);

            return redirect()->back()->with('success', 'A new password has been sent to '.$user->email);
        } catch (\Exception $e) {
            Log::error('Admin Password Reset Failed for user '.$user->id.': '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to send a new password.');
        }
    }

    private function sendNewPassword(User $user): void
    {
        $newPassword = Str::random(8);
        $user->password = bcrypt($newPassword);
        $user->save();

        Mail::to($user->email)->send(new CustomerPasswordMail($newPassword));
    }
}
