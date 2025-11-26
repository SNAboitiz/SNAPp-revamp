<x-layouts.app>
    <form method="POST" action="{{ $profile->id ? route('profiles.update', $profile->id) : route('profiles.store') }}">
        @csrf
        @if ($profile->id)
            @method('PUT')
        @endif

        <div class="h-full w-full px-4 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">

                <!-- First Card - Account Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Account Information</h2>
                    </div>
                    <flux:field>
                        <!-- Account Name - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Account Name" placeholder="Enter Account Name" name="account_name"
                                value="{{ old('account_name', $profile->account_name) }}" />
                        @else
                            <flux:input label="Account Name" placeholder="Enter Account Name" name="account_name"
                                value="{{ old('account_name', $profile->account_name) }}" readonly variant="filled" />
                        @endcan

                        <!-- Short Name - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Short Name" placeholder="Enter Short Name" name="short_name"
                                value="{{ old('short_name', $profile->short_name) }}" />
                        @else
                            <flux:input label="Short Name" placeholder="Enter Short Name" name="short_name"
                                value="{{ old('short_name', $profile->short_name) }}" readonly variant="filled" />
                        @endcan

                        <!-- Business Address - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Business Address" placeholder="Enter Business Address"
                                name="business_address" value="{{ old('business_address', $profile->business_address) }}" />
                        @else
                            <flux:input badge="customer" label="Business Address" placeholder="Enter Business Address"
                                name="business_address" value="{{ old('business_address', $profile->business_address) }}"
                                readonly variant="filled" />
                        @endcan

                        <!-- Facility Address - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Facility Address" placeholder="Enter Facility Address"
                                name="facility_address" value="{{ old('facility_address', $profile->facility_address) }}" />
                        @else
                            <flux:input badge="customer" label="Facility Address" placeholder="Enter Facility Address"
                                name="facility_address" value="{{ old('facility_address', $profile->facility_address) }}"
                                readonly variant="filled" />
                        @endcan

                        <!-- Customer Category - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Customer Category" placeholder="Enter Customer Category"
                                name="customer_category"
                                value="{{ old('customer_category', $profile->customer_category) }}" />
                        @else
                            <flux:input label="Customer Category" placeholder="Enter Customer Category"
                                name="customer_category"
                                value="{{ old('customer_category', $profile->customer_category) }}" readonly
                                variant="filled" />
                        @endcan
                    </flux:field>
                </div>

                <!-- Second Card - Contract Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Contract Information</h2>
                    </div>
                    <flux:field>
                        <!-- Start Date - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Cooperation Period Start Date" placeholder="Enter Start Date" type="date"
                                name="cooperation_period_start_date"
                                value="{{ old('cooperation_period_start_date', $profile->cooperation_period_start_date) }}" />
                        @else
                            <flux:input label="Cooperation Period Start Date" placeholder="Enter Start Date" type="date"
                                name="cooperation_period_start_date"
                                value="{{ old('cooperation_period_start_date', $profile->cooperation_period_start_date) }}"
                                readonly variant="filled" />
                        @endcan

                        <!-- End Date - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Cooperation Period End Date" placeholder="Enter End Date" type="date"
                                name="cooperation_period_end_date"
                                value="{{ old('cooperation_period_end_date', $profile->cooperation_period_end_date) }}" />
                        @else
                            <flux:input label="Cooperation Period End Date" placeholder="Enter End Date" type="date"
                                name="cooperation_period_end_date"
                                value="{{ old('cooperation_period_end_date', $profile->cooperation_period_end_date) }}"
                                readonly variant="filled" />
                        @endcan

                        <!-- Contract Price - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Contract Price" placeholder="Enter Contract Price" name="contract_price"
                                value="{{ old('contract_price', $profile->contract_price) }}" />
                        @else
                            <flux:input label="Contract Price" placeholder="Enter Contract Price" name="contract_price"
                                value="{{ old('contract_price', $profile->contract_price) }}" readonly variant="filled" />
                        @endcan

                        <!-- Contract Demand - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Contract Demand" placeholder="Enter Contract Demand" name="contracted_demand" value="{{ old('contracted_demand', $profile->contracted_demand) }}" />
                        @else
                            <flux:input label="Contract Demand" placeholder="Enter Contract Demand" name="contracted_demand" value="{{ old('contracted_demand', $profile->contracted_demand) }}" readonly variant="filled" />
                        @endcan

                        <!-- Certificate of Contestability No. - AE editable only -->
                        @can('profile-editable-AE')
                            <flux:input label="Certificate of Contestability No." placeholder="Enter Certificate No."
                                name="certificate_of_contestability_number"
                                value="{{ old('certificate_of_contestability_number', $profile->certificate_of_contestability_number) }}" />
                        @else
                            <flux:input label="Certificate of Contestability No." placeholder="Enter Certificate No."
                                name="certificate_of_contestability_number"
                                value="{{ old('certificate_of_contestability_number', $profile->certificate_of_contestability_number) }}"
                                readonly variant="filled" />
                        @endcan
                    </flux:field>
                    <!-- Other Info - Customer editable only -->
                    @can('profile-editable-customer')
                        <flux:input badge="customer" label="Other Information" placeholder="Enter Additional Info"
                            name="other_information"
                            value="{{ old('other_information', $profile->other_information) }}" />
                    @else
                        <flux:input badge="customer" label="Other Information" placeholder="Enter Additional Info"
                            name="other_information" value="{{ old('other_information', $profile->other_information) }}"
                            readonly variant="filled" />
                    @endcan
                </div>

                <!-- Third Card - Contact Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Contact Information</h2>
                    </div>
                    <flux:field>
                        <!-- Contact Name - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Contact Name" placeholder="Enter Contact Name"
                                name="contact_name" value="{{ old('contact_name', $profile->contact_name) }}" />
                        @else
                            <flux:input badge="customer" label="Contact Name" placeholder="Enter Contact Name"
                                name="contact_name" value="{{ old('contact_name', $profile->contact_name) }}" readonly
                                variant="filled" />
                        @endcan

                        <!-- Designation - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Designation" placeholder="Enter Designation"
                                name="designation" value="{{ old('designation', $profile->designation) }}" />
                        @else
                            <flux:input badge="customer" label="Designation" placeholder="Enter Designation"
                                name="designation" value="{{ old('designation', $profile->designation) }}" readonly
                                variant="filled" />
                        @endcan

                        <!-- Email - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Email" placeholder="Enter Email" type="email"
                                name="email" value="{{ old('email', $profile->email) }}" />
                        @else
                            <flux:input badge="customer" label="Email" placeholder="Enter Email" type="email"
                                name="email" value="{{ old('email', $profile->email) }}" readonly variant="filled" />
                        @endcan

                        <!-- Mobile - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="customer" label="Mobile Number" placeholder="Enter Mobile Number"
                                type="tel" name="mobile_number"
                                value="{{ old('mobile_number', $profile->mobile_number) }}" />
                        @else
                            <flux:input badge="customer" label="Mobile Number" placeholder="Enter Mobile Number"
                                type="tel" name="mobile_number"
                                value="{{ old('mobile_number', $profile->mobile_number) }}" readonly variant="filled" />
                        @endcan
                    </flux:field>
                </div>

                <!-- Fourth Card - Secondary Contact -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold">Secondary Contact</h2>
                    </div>
                    <flux:field>
                        <!-- Contact Name 1 - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="secondary" label="Contact Name" placeholder="Enter Contact Name"
                                name="contact_name_1" value="{{ old('contact_name_1', $profile->contact_name_1) }}" />
                        @else
                            <flux:input badge="secondary" label="Contact Name" placeholder="Enter Contact Name"
                                name="contact_name_1" value="{{ old('contact_name_1', $profile->contact_name_1) }}"
                                readonly variant="filled" />
                        @endcan

                        <!-- Designation 1 - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="secondary" label="Designation" placeholder="Enter Designation"
                                name="designation_1" value="{{ old('designation_1', $profile->designation_1) }}" />
                        @else
                            <flux:input badge="secondary" label="Designation" placeholder="Enter Designation"
                                name="designation_1" value="{{ old('designation_1', $profile->designation_1) }}" readonly
                                variant="filled" />
                        @endcan

                        <!-- Email 1 - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="secondary" label="Email" placeholder="Enter Email" type="email"
                                name="email_1" value="{{ old('email_1', $profile->email_1) }}" />
                        @else
                            <flux:input badge="secondary" label="Email" placeholder="Enter Email" type="email"
                                name="email_1" value="{{ old('email_1', $profile->email_1) }}" readonly
                                variant="filled" />
                        @endcan

                        <!-- Mobile Number 1 - Customer editable only -->
                        @can('profile-editable-customer')
                            <flux:input badge="secondary" label="Mobile Number" placeholder="Enter Mobile Number"
                                type="tel" name="mobile_number_1"
                                value="{{ old('mobile_number_1', $profile->mobile_number_1) }}" />
                        @else
                            <flux:input badge="secondary" label="Mobile Number" placeholder="Enter Mobile Number"
                                type="tel" name="mobile_number_1"
                                value="{{ old('mobile_number_1', $profile->mobile_number_1) }}" readonly
                                variant="filled" />
                        @endcan
                    </flux:field>
                </div>
            </div>
        </div>

        <!-- Save and Back buttons -->
        <div class="flex justify-between px-4 py-4">
            <flux:button type="submit" variant="primary">
                Save Changes
            </flux:button>
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Back</a>
        </div>
    </form>
</x-layouts.app>
