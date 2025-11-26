<?php

use App\Http\Controllers\AdvisoryController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTaxDocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GhgController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Security\PermissionController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\RolePermission;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('account/deactivated', function () {
    return view('auth.deactivated');
})->name('account.deactivated');

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    // ALL ACCESS
    Route::middleware(['role:admin|account executive|customer'])->group(function () {
        Route::get('/help', function () {
            return view('help');
        })->name('help');
<<<<<<< Updated upstream
        Route::resource('profiles', ProfileController::class)->only([
            'index',
            'edit',
            'update',
        ]);
        // Route::resource('profiles', ProfileController::class);
=======
        Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');
        Route::get('profiles/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
        Route::post('profiles', [ProfileController::class, 'store'])->name('profiles.store');
        Route::put('profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');

>>>>>>> Stashed changes
        Route::get('/dashboard/load-more', [DashboardController::class, 'loadMore'])->name('dashboard.load-more');
        Route::post('contracts/store', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('contracts', [ContractController::class, 'showContractsPage'])->name('my-contracts');
        Route::get('/dashboard', [DashboardController::class, 'showDashboardFields'])->name('dashboard');
        Route::get('/advisories', [AdvisoryController::class, 'index'])->name('advisories.index');
        Route::get('/advisories/load-more', [AdvisoryController::class, 'loadMore'])->name('advisories.load-more');
    });
    Route::middleware(['role:admin|customer'])->group(function () {
        Route::get('/my-bills', [BillController::class, 'showBillsPage'])->name('bills.show');
        Route::get('/payment-history', [BillController::class, 'showPaymentHistory'])->name('payments.history');
        Route::get('/bills/export', [BillController::class, 'exportBills'])->name('bills.export');
        Route::get('/payments/export', [BillController::class, 'exportPayments'])->name('payments.export');
        Route::get('/energy-consumption', [GhgController::class, 'calculateEmissions'])->name('energy-consumption');
        Route::post(
            '/admin/customers/{customer}/facilities/{facility?}/tax-documents',
            [CustomerTaxDocumentController::class, 'store']
        )->name('tax-documents.store');
    });

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Admin-only routes inside role:admin middleware
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('/all-user-list/{user}', [UserController::class, 'editAllUsers'])->name('all-user-list.update');
        Route::get('/admin-list', [UserController::class, 'showAdmins'])->name('admin-list');
        Route::post('/users/store-admin', [UserController::class, 'storeAdmins'])->name('admin.users.store-admin');
        Route::put('/users/{user}/update-admin', [UserController::class, 'updateAdmins'])->name('admin.users.update-admin');
        Route::post('/users/store-account-executive', [UserController::class, 'storeAE'])->name('admin.users.store-account-executive');
        Route::get('/account-executive-list', [UserController::class, 'showAE'])->name('account-executive-list');
        Route::get('/all-user-list', [UserController::class, 'showAllUsers'])->name('all-user-list');
        Route::put('/users/{user}/update-account-executive', [UserController::class, 'updateAE'])->name('admin.users.update-account-executive');
        Route::post('/users/{user}/reset-password', [App\Http\Controllers\UserController::class, 'resetPassword'])->name('users.reset-password');

        Route::get('/role-permission', [RolePermission::class, 'index'])->name('role.permission.list');
        Route::post('/role-permission', [RolePermission::class, 'store'])->name('role.permission.store');
        Route::resource('permission', PermissionController::class)->except(['destroy']);
        Route::resource('role', RoleController::class);
        Route::delete('roles/{id}', [RolePermission::class, 'destroyRole'])->name('roles.destroy');
        Route::delete('permissions/{id}', [RolePermission::class, 'destroyPermission'])->name('permission.destroy');

        Route::get('/advisories', [AdvisoryController::class, 'adminList'])->name('advisories.list');
        Route::post('/advisories', [AdvisoryController::class, 'store'])->name('advisories.store');
        Route::put('/advisories/{advisory}', [AdvisoryController::class, 'update'])->name('advisories.update');

        Route::get('profiles', [ProfileController::class, 'profileList'])->name('admin.profiles.list');
        Route::get('profiles/{profile}/edit', [ProfileController::class, 'editProfileForm'])->name('admin.profiles.edit');
        Route::get('profiles/facilities', [ProfileController::class, 'getFacilitiesByCustomer'])->name('admin.profiles.get-facilities');
        Route::put('profiles/{profile}', [ProfileController::class, 'updateProfile'])->name('admin.profiles.update');
        Route::delete('profiles/{profile}', [ProfileController::class, 'destroy'])->name('admin.profiles.destroy');

        Route::get('/bills/manage', [BillController::class, 'showManageBillsPage'])->name('bills.manage');
        Route::post('/bills/upload', [BillController::class, 'uploadBills'])->name('bills.upload');
        //this is the cutomers route
        Route::resource('customers', CustomerController::class);
        Route::resource('facilities', FacilityController::class);
    });
});

require __DIR__.'/auth.php';

Route::webhooks('webhook/inquiry');
