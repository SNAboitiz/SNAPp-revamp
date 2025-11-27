<div x-data="{}" x-init="@if (session('show_modal') === 'edit-customer-profile-modal') $nextTick(() => $flux.modal('edit-customer-profile-modal').show()) @endif">
    <flux:modal name="edit-customer-profile-modal" class="max-w-5xl">
        <form method="POST" id="edit-customer-form"
            data-base-action="{{ route('admin.profiles.update', ['profile' => ':profile_id']) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <flux:heading size="lg">Edit Customer Profile</flux:heading>
                <flux:text class="mt-2">Required: Customer & Facility</flux:text>
            </div>

            @if($errors->has('duplicate'))
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $errors->first('duplicate') }}
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                {{-- Hidden fields for customer and facility --}}
                <input type="hidden" name="edit_customer_id" id="hidden_customer_id" />
                <input type="hidden" name="edit_facility_id" id="hidden_facility_id" />

                {{-- IDENTIFIERS --}}
                <!-- Customer Read-Only -->
                <flux:field class="md:col-span-4">
                    <flux:label>Customer</flux:label>
                    <input type="text" id="readonly_customer_name" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700" />
                </flux:field>

                <!-- Facility Read-Only -->
                <flux:field class="md:col-span-4">
                    <flux:label>Facility</flux:label>
                    <input type="text" id="readonly_facility_name" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700" />
                </flux:field>

                <flux:field class="md:col-span-4">
                    <flux:label>Customer Category</flux:label>
                    <flux:input name="edit_customer_category" />
                    @error('edit_customer_category')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                {{-- CONTRACT --}}
                <flux:field class="md:col-span-2">
                    <flux:label>Contract Price</flux:label>
                    <flux:input name="edit_contract_price" />
                    @error('edit_contract_price')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label>Contracted Demand</flux:label>
                    <flux:input name="edit_contracted_demand" />
                    @error('edit_contracted_demand')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-4">
                    <flux:label>Certificate of Contestability #</flux:label>
                    <flux:input name="edit_certificate_of_contestability_number" />
                    @error('edit_certificate_of_contestability_number')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                {{-- DATES --}}
                <flux:field class="md:col-span-2">
                    <flux:label>Coop Start</flux:label>
                    <flux:input type="date" name="edit_cooperation_period_start_date" />
                    @error('edit_cooperation_period_start_date')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label>Coop End</flux:label>
                    <flux:input type="date" name="edit_cooperation_period_end_date" />
                    @error('edit_cooperation_period_end_date')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                {{-- ADDRESSES --}}
                <flux:field class="md:col-span-6">
                    <flux:label>Business Address</flux:label>
                    <flux:input name="edit_business_address" />
                    @error('edit_business_address')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-6">
                    <flux:label>Facility Address</flux:label>
                    <flux:input name="edit_facility_address" />
                    @error('edit_facility_address')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                {{-- CONTACT --}}
                <flux:field class="md:col-span-3">
                    <flux:label>Contact Name</flux:label>
                    <flux:input name="edit_contact_name" />
                    @error('edit_contact_name')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label>Designation</flux:label>
                    <flux:input name="edit_designation" />
                    @error('edit_designation')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label>Mobile Number</flux:label>
                    <flux:input name="edit_mobile_number" />
                    @error('edit_mobile_number')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label>Email Address</flux:label>
                    <flux:input name="edit_email" />
                    @error('edit_email')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label badge="Secondary">Contact Name</flux:label>
                    <flux:input name="edit_contact_name_1" />
                    @error('edit_contact_name_1')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label badge="Secondary">Designation</flux:label>
                    <flux:input name="edit_designation_1" />
                    @error('edit_designation_1')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label badge="Secondary">Mobile Number</flux:label>
                    <flux:input name="edit_mobile_number_1" />
                    @error('edit_mobile_number_1')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-3">
                    <flux:label badge="Secondary">Email Address</flux:label>
                    <flux:input name="edit_email_1" />
                    @error('edit_email_1')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                {{-- NOTES --}}
                <flux:field class="md:col-span-12">
                    <flux:label>Other Information</flux:label>
                    <flux:input name="edit_other_information" placeholder="Any remarks or notes" />
                    @error('edit_other_information')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>
            </div>

            <div class="flex pt-6">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save Changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>

<script>
    // Populate fields when row is clicked
    document.addEventListener('click', function(event) {
        const row = event.target.closest('.flux-btn-info');
        if (!row) return;

        const form = document.getElementById('edit-customer-form');
        const ds = row.dataset;

        // Update form action
        form.action = form.dataset.baseAction.replace(':profile_id', ds.id);

        // Set hidden fields
        document.getElementById('hidden_customer_id').value = ds.customerId || '';
        document.getElementById('hidden_facility_id').value = ds.facilityId || '';

        // Get customer and facility names from table cells
        const customerName = row.cells[0].textContent.trim();
        const facilityName = row.cells[1].textContent.trim();

        // Set readonly display fields
        document.getElementById('readonly_customer_name').value = customerName;
        document.getElementById('readonly_facility_name').value = facilityName;

        // Helper to set field values
        const set = (name, val) => {
            const el = form.querySelector(`[name="${name}"]`);
            if (el) el.value = val || '';
        };

        // Populate all fields from data attributes
        set('edit_customer_category', ds.customerCategory);
        set('edit_contract_price', ds.contractPrice);
        set('edit_contracted_demand', ds.contractedDemand);
        set('edit_cooperation_period_start_date', ds.startDate);
        set('edit_cooperation_period_end_date', ds.endDate);
        set('edit_business_address', ds.businessAddress);
        set('edit_facility_address', ds.facilityAddress);
        set('edit_contact_name', ds.contactName);
        set('edit_designation', ds.designation);
        set('edit_mobile_number', ds.mobileNumber);
        set('edit_email', ds.emailAddress);
        set('edit_other_information', ds.otherInformation);
        set('edit_certificate_of_contestability_number', ds.certificateOfContestabilityNumber);
        set('edit_contact_name_1', ds.contactName1);
        set('edit_designation_1', ds.designation1);
        set('edit_mobile_number_1', ds.mobileNumber1);
        set('edit_email_1', ds.emailAddress1);
    });

    // CRITICAL FIX: Prevent default form submission and ensure _method field exists
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('edit-customer-form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default submission
            
            // Ensure _method field exists and is set to PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            } else {
                methodField.value = 'PUT';
            }
            
            // Ensure CSRF token exists
            let csrfField = form.querySelector('input[name="_token"]');
            if (!csrfField) {
                csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                form.appendChild(csrfField);
            }
            
            console.log('Submitting with method:', methodField.value);
            console.log('Action:', form.action);
            
            // Now submit the form
            form.submit();
        });
    });
</script>