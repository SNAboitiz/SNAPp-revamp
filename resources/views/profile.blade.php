<x-layouts.app>
    <form method="POST" action="">
        @csrf
        <div class="h-full w-full px-4 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">

                <!-- First Card - Account Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6 relative">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#1443e0]">Account Information</h2>
                        <a href="{{ route('profiles.edit', $profile->id ?? 0) }}"
                            class="absolute top-4 right-4 inline-flex items-center p-2 bg-white rounded-full hover:bg-gray-100 transition-colors">
                            <flux:icon.edit class="w-6 h-6 text-[#1443e0]" />
                        </a>

                    </div>
                    <flux:field>
                        <!-- Account Name -->
                        <flux:input label="Account Name" placeholder="Enter Account Name"
                            value="{{ $profiles->account_name ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Short Name -->
                        <flux:input label="Short Name" placeholder="Enter Short Name"
                            value="{{ $profiles->short_name ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Business Address -->
                        <flux:input badge="customer" label="Business Address" placeholder="Enter Business Address"
                            value="{{ $profiles->business_address ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Facility Address -->
                        <flux:input badge="customer" label="Facility Address" placeholder="Enter Facility Address"
                            value="{{ $profiles->facility_address ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Customer Category -->
                        <flux:input label="Customer Category" placeholder="Enter Customer Category"
                            value="{{ $profiles->customer_category ?? 'N/A' }}" readonly variant="filled" />
                    </flux:field>
                </div>

                <!-- Second Card - Contract Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6 relative">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#1443e0]">Contract Information</h2>
                        <a href="{{ route('profiles.edit', $profile->id ?? 0) }}"
                            class="absolute top-4 right-4 inline-flex items-center p-2 bg-white rounded-full hover:bg-gray-100 transition-colors">
                            <flux:icon.edit class="w-6 h-6 text-[#1443e0]" />
                        </a>

                    </div>
                    <flux:field>
                        <!-- Start Date -->
                        <flux:input label="Cooperation Period Start Date" placeholder="Enter Start Date" type="date"
                            value="{{ $profiles->cooperation_period_start_date ?? 'N/A' }}" readonly variant="filled" />

                        <!-- End Date -->
                        <flux:input label="Cooperation Period End Date" placeholder="Enter End Date" type="date"
                            value="{{ $profiles->cooperation_period_end_date ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Contract Price -->
                        <flux:input label="Contract Price" placeholder="Enter Contract Price"
                            value="{{ $profiles->contract_price ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Contract Demand -->
                        <flux:input label="Contract Demand" placeholder="Enter Contract Demand"
                            value="{{ $profiles->contracted_demand ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Certificate of Contestability Number -->
                        <flux:input label="Certificate of Contestability No." placeholder="Enter Certificate No."
                            value="{{ $profiles->certificate_of_contestability_number ?? 'N/A' }}" readonly
                            variant="filled" />

                        <!-- Other Info -->
                        <flux:input badge="customer" label="Other Information" placeholder="Enter Additional Info"
                            value="{{ $profiles->other_information ?? 'N/A' }}" readonly variant="filled" />


                    </flux:field>
                </div>

                <!-- Third Card - Contact Information -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6 relative">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#1443e0]">Contact Information</h2>
                        <a href="{{ route('profiles.edit', $profile->id ?? 0) }}"
                            class="absolute top-4 right-4 inline-flex items-center p-2 bg-white rounded-full hover:bg-gray-100 transition-colors">
                            <flux:icon.edit class="w-6 h-6 text-[#1443e0]" />
                        </a>

                    </div>
                    <flux:field>
                        <!-- Contact Name -->
                        <flux:input badge="customer" label="Contact Name" placeholder="Enter Contact Name"
                            value="{{ $profiles->contact_name ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Designation -->
                        <flux:input badge="customer" label="Designation" placeholder="Enter Designation"
                            value="{{ $profiles->designation ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Email -->
                        <flux:input badge="customer" label="Email" placeholder="Enter Email" type="email"
                            value="{{ $profiles->email ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Mobile -->
                        <flux:input badge="customer" label="Mobile Number" placeholder="Enter Mobile Number"
                            type="tel" value="{{ $profiles->mobile_number ?? 'N/A' }}" readonly variant="filled" />
                    </flux:field>
                </div>

                <!-- Fourth Card - Secondary Contact -->
                <div class="flex flex-col bg-white rounded-2xl shadow p-6 relative">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#1443e0]">Secondary Contact</h2>
                        <a href="{{ route('profiles.edit', $profile->id ?? 0) }}"
                            class="absolute top-4 right-4 inline-flex items-center p-2 bg-white rounded-full hover:bg-gray-100 transition-colors">
                            <flux:icon.edit class="w-6 h-6 text-[#1443e0]" />
                        </a>
                    </div>
                    <flux:field>
                        <!-- Contact Name 1 -->
                        <flux:input badge="secondary" label="Contact Name" placeholder="Enter Contact Name"
                            value="{{ $profiles->contact_name_1 ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Designation 1 -->
                        <flux:input badge="secondary" label="Designation" placeholder="Enter Designation"
                            value="{{ $profiles->designation_1 ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Email 1 -->
                        <flux:input badge="secondary" label="Email" placeholder="Enter Email" type="email"
                            value="{{ $profiles->email_1 ?? 'N/A' }}" readonly variant="filled" />

                        <!-- Mobile Number 1 -->
                        <flux:input badge="secondary" label="Mobile Number" placeholder="Enter Mobile Number"
                            type="tel" value="{{ $profiles->mobile_number_1 ?? 'N/A' }}" readonly
                            variant="filled" />
                    </flux:field>
                </div>

            </div>
        </div>
        <!-- Save and Back buttons -->
        <div class="flex justify-between px-4 py-4">

        </div>
    </form>
</x-layouts.app>
