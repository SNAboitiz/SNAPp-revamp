<div x-data="{
    showModal: @js(session('show_modal') === 'upload-bills'),
    selectedCustomer: '{{ old('customer_id') }}',
    selectedFacility: '{{ old('facility_id') }}', // <-- add this
    allFacilities: @js($facilities->toArray() ?? []),
    filteredFacilities: [],
    filterFacilities(customerId) {
        if (!customerId) {
            this.filteredFacilities = [];
            this.selectedFacility = ''; // <-- reset facility if no customer
            return;
        }
        this.filteredFacilities = this.allFacilities.filter(f => f.customer_id == customerId);
        this.selectedFacility = ''; // <-- reset facility on customer change
    }
}" x-init="if (showModal) { $nextTick(() => $flux.modal('upload-bills').show()); }
if (selectedCustomer) { filterFacilities(selectedCustomer); }">
    <flux:modal name="upload-bills" class="md:max-w-3xl">
        <form action="{{ route('bills.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <flux:heading size="lg">Upload Bill</flux:heading>
                <flux:text class="mt-2">
                    Bill upload accepts pdf format.
                </flux:text>
            </div>

            {{-- Customer Dropdown --}}
            <flux:field>
                <flux:label badge="Required">Select Customer</flux:label>
                <flux:select name="customer_id" placeholder="— Select customer —" required x-model="selectedCustomer"
                    @change="filterFacilities($event.target.value); $el.form.querySelector('[name=facility_id]').value = ''"
                    :error="$errors->first('customer_id')">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" class="text-black"
                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->account_name }} ({{ $customer->short_name }})
                        </option>
                    @endforeach
                </flux:select>
                @error('customer_id')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            {{-- Facility Dropdown --}}
            <flux:field>
                <flux:label>Select Facility (Optional)</flux:label>
                <flux:select name="facility_id" placeholder="— Select facility —" x-model="selectedFacility">
                    <template x-if="!selectedCustomer">
                        <option value="">Select customer first</option>
                    </template>
                    <template x-if="selectedCustomer && filteredFacilities.length === 0">
                        <option value="">No facilities available for this customer</option>
                    </template>
                    <template x-if="selectedCustomer && filteredFacilities.length > 0">
                        <option value="">— None / Skip —</option>
                    </template>
                    <template x-for="facility in filteredFacilities" :key="facility.id">
                        <option :value="facility.id" x-text="`${facility.name} (SEIN: ${facility.sein})`"></option>
                    </template>
                </flux:select>

                @error('facility_id')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            {{-- Two-column date & number --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                <flux:field>
                    <flux:label badge="Required">Billing Start Date</flux:label>
                    <flux:input name="billing_start_date" type="date" value="{{ old('billing_start_date') }}"
                        required />
                    @error('billing_start_date')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Billing End Date</flux:label>
                    <flux:input name="billing_end_date" type="date" value="{{ old('billing_end_date') }}"
                        required />
                    @error('billing_end_date')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label badge="Required">Bill Number</flux:label>
                    <flux:input name="bill_number" value="{{ old('bill_number') }}"
                        placeholder="Enter unique bill number" required />
                    @error('bill_number')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:input type="file" name="file_path" label="Document" badge="Required" accept=".pdf"
                        required />
                    @error('file_path')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <flux:button type="submit" variant="primary">Upload Bill</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
