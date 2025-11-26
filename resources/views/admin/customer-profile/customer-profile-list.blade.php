<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Customers</h2>
            <div class="flex gap-2">
                <flux:modal.trigger name="customer-profile-modal">
                    <flux:button variant="primary">
                        Add Customer
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.profiles.list') }}" class="mb-4 flex flex-wrap items-center gap-4">
            <flux:input icon="magnifying-glass" name="search" placeholder="Search account name..."
                value="{{ request('search') }}" class="w-full md:w-1/4" />

            <flux:button type="submit" variant="primary" class="self-end">
                Apply Filters
            </flux:button>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Short Name</th>
                        <th>Business Address</th>
                        <th>Facility Address</th>
                        <th>Category</th>
                        <th>Customer ID</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Contact Name</th>
                        <th>Designation</th>
                        <th>Contract Price</th>
                        <th>Demand</th>
                        <th>Period</th>
                        <th>Other Info</th>
                        <th>Account Executive</th>
                        <th>Certificate #</th>
                        <th>Contact Name (2)</th>
                        <th>Designation (2)</th>
                        <th>Mobile (2)</th>
                        <th>Email (2)</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($profiles as $profile)
                        @php $user = $profile->users->first(); @endphp
                        <tr class="cursor-pointer hover:bg-gray-100 transition flux-btn-info"
                            data-id="{{ $profile->id }}" data-name="{{ $user?->name }}"
                            data-email="{{ $user?->email }}" data-customer-id="{{ $profile->customer_id }}"
                            data-account-name="{{ $profile->account_name }}"
                            data-short-name="{{ $profile->short_name }}"
                            data-customer-category="{{ $profile->customer_category }}"
                            data-contract-price="{{ $profile->contract_price }}"
                            data-contracted-demand="{{ $profile->contracted_demand }}"
                            data-start-date="{{ $profile->cooperation_period_start_date }}"
                            data-end-date="{{ $profile->cooperation_period_end_date }}"
                            data-business-address="{{ $profile->business_address }}"
                            data-facility-address="{{ $profile->facility_address }}"
                            data-contact-name="{{ $profile->contact_name }}"
                            data-designation="{{ $profile->designation }}"
                            data-mobile-number="{{ $profile->mobile_number }}"
                            data-email-address="{{ $profile->email }}"
                            data-other-information="{{ $profile->other_information }}"
                            data-contact-name1="{{ $profile->contact_name_1 }}"
                            data-designation1="{{ $profile->designation_1 }}"
                            data-mobile-number1="{{ $profile->mobile_number_1 }}"
                            data-email-address1="{{ $profile->email_1 }}"
                            data-certificate-of-contestability-number="{{ $profile->certificate_of_contestability_number }}"
                            onclick="document.getElementById('open-edit-profile-modal').click()">

                            <td>{{ $profile->account_name ?? '—' }}</td>
                            <td>{{ $profile->short_name ?? '—' }}</td>
                            <td>{{ $profile->business_address ?? '—' }}</td>
                            <td>{{ $profile->facility_address ?? '—' }}</td>
                            <td>{{ $profile->customer_category ?? '—' }}</td>
                            <td>{{ $profile->customer_id ?? '—' }}</td>
                            <td>{{ $profile->email ?? '—' }}</td>
                            <td>{{ $profile->mobile_number ?? '—' }}</td>
                            <td>{{ $profile->contact_name ?? '—' }}</td>
                            <td>{{ $profile->designation ?? '—' }}</td>
                            <td>{{ $profile->contract_price ?? '—' }}</td>
                            <td>{{ $profile->contracted_demand ?? '—' }}</td>
                            <td>
                                {{ $profile->cooperation_period_start_date ?? '—' }} —
                                {{ $profile->cooperation_period_end_date ?? '—' }}
                            </td>
                            <td>{{ $profile->other_information ?? '—' }}</td>
                            <td>{{ $profile->account_executive ?? '—' }}</td>
                            <td>{{ $profile->certificate_of_contestability_number ?? '—' }}</td>
                            <td>{{ $profile->contact_name_1 ?? '—' }}</td>
                            <td>{{ $profile->designation_1 ?? '—' }}</td>
                            <td>{{ $profile->mobile_number_1 ?? '—' }}</td>
                            <td>{{ $profile->email_1 ?? '—' }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal trigger for edit -->
        <flux:modal.trigger name="edit-customer-profile-modal">
            <button id="open-edit-profile-modal" class="hidden"></button>
        </flux:modal.trigger>
    </div>

    <!-- Modals -->
    @include('admin.customer-profile.form-edit-customer-profile')
    @include('admin.customer-profile.form-create-customer-profile')
</x-layouts.app>
