<div x-data="{}" x-init="@if (session('show_modal') === 'customer-profile-modal') $nextTick(() => $flux.modal('customer-profile-modal').show()) @endif">
    <flux:modal name="customer-profile-modal" class="max-w-5xl">
        <form action="{{ route('admin.profiles.store') }}" method="POST" class="space-y-10" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">Create New Customer</flux:heading>
                <flux:text class="mt-2">Fields marked "Required" must be filled out.</flux:text>
            </div>

            {{-- SECTION: Customer Identification --}}
            <div class="space-y-4">
                <flux:heading size="md">Customer Identification</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-2">
                        <flux:label badge="Required">Customer ID</flux:label>
                        <flux:input name="customer_id" value="{{ old('customer_id') }}" />
                        @error('customer_id')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-2">
                        <flux:label badge="Required">Short Name</flux:label>
                        <flux:input name="short_name" value="{{ old('short_name') }}" />
                        @error('short_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-4">
                        <flux:label>Account Name</flux:label>
                        <flux:input name="account_name" value="{{ old('account_name') }}" />
                        @error('account_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-4">
                        <flux:label>Customer Category</flux:label>
                        <flux:input name="customer_category" value="{{ old('customer_category') }}" />
                        @error('customer_category')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>

            {{-- SECTION: Address Details --}}
            <div class="space-y-4">
                <flux:heading size="md">Address Information</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-6">
                        <flux:label>Business Address</flux:label>
                        <flux:input name="business_address" value="{{ old('business_address') }}" />
                        @error('business_address')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-6">
                        <flux:label>Facility Address</flux:label>
                        <flux:input name="facility_address" value="{{ old('facility_address') }}" />
                        @error('facility_address')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>

            {{-- SECTION: Contract Details --}}
            <div class="space-y-4">
                <flux:heading size="md">Contract Details</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-4">
                        <flux:label>Certificate of Contestability #</flux:label>
                        <flux:input name="certificate_of_contestability_number"
                            value="{{ old('certificate_of_contestability_number') }}" />
                        @error('certificate_of_contestability_number')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-4">
                        <flux:label>Contract Price</flux:label>
                        <flux:input name="contract_price" value="{{ old('contract_price') }}" />
                        @error('contract_price')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-4">
                        <flux:label>Contracted Demand</flux:label>
                        <flux:input name="contracted_demand" value="{{ old('contracted_demand') }}" />
                        @error('contracted_demand')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-6">
                        <flux:label>Cooperation Start Date</flux:label>
                        <flux:input type="date" name="cooperation_period_start_date"
                            value="{{ old('cooperation_period_start_date') }}" />
                        @error('cooperation_period_start_date')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-6">
                        <flux:label>Cooperation End Date</flux:label>
                        <flux:input type="date" name="cooperation_period_end_date"
                            value="{{ old('cooperation_period_end_date') }}" />
                        @error('cooperation_period_end_date')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>

            {{-- SECTION: Primary Contact --}}
            <div class="space-y-4">
                <flux:heading size="md">Primary Contact</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-3">
                        <flux:label>Contact Name</flux:label>
                        <flux:input name="contact_name" value="{{ old('contact_name') }}" />
                        @error('contact_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label>Designation</flux:label>
                        <flux:input name="designation" value="{{ old('designation') }}" />
                        @error('designation')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label>Mobile Number</flux:label>
                        <flux:input name="mobile_number" value="{{ old('mobile_number') }}" />
                        @error('mobile_number')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label>Email Address</flux:label>
                        <flux:input name="email" value="{{ old('email') }}" />
                        @error('email')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>
            {{-- SECTION: Secondary Contact --}}
            <div class="space-y-4">
                <flux:heading size="md">Alternate Contact <span
                        class="ml-2 text-sm text-gray-500">(Optional)</span></flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-3">
                        <flux:label badge="Secondary">Contact Name </flux:label>
                        <flux:input name="contact_name_1" value="{{ old('contact_name_1') }}" />
                        @error('contact_name_1')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label badge="Secondary">Designation </flux:label>
                        <flux:input name="designation_1" value="{{ old('designation_1') }}" />
                        @error('designation_1')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label badge="Secondary">Mobile Number </flux:label>
                        <flux:input name="mobile_number_1" value="{{ old('mobile_number_1') }}" />
                        @error('mobile_number_1')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>

                    <flux:field class="md:col-span-3">
                        <flux:label badge="Secondary">Email Address </flux:label>
                        <flux:input name="email_1" value="{{ old('email_1') }}" />
                        @error('email_1')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>


            {{-- SECTION: Notes --}}
            <div class="space-y-4">
                <flux:heading size="md">Additional Info</flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <flux:field class="md:col-span-12">
                        <flux:label>Other Information</flux:label>
                        <flux:input name="other_information" value="{{ old('other_information') }}"
                            placeholder="Any remarks or notes" />
                        @error('other_information')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </flux:field>
                </div>
            </div>

            <div class="flex pt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Create Customer</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
