<div x-data="{
    showModal: @js(session('show_modal') === 'form-upload-contract'),
    selectedCustomer: '{{ old('customer_id') }}',
    allFacilities: @js($facilities->toArray()),
    filteredFacilities: [],
    loadingFacilities: false,
    filterFacilities(customerId) {
        console.log('Filtering facilities for customer:', customerId);

        if (!customerId) {
            this.filteredFacilities = [];
            return;
        }

        // Filter facilities by customer_id
        this.filteredFacilities = this.allFacilities.filter(f => f.customer_id == customerId);
        console.log('Filtered facilities:', this.filteredFacilities);
    }
}" x-init="console.log('All facilities loaded:', allFacilities);
if (showModal) {
    $nextTick(() => $flux.modal('upload-contract').show());
}
if (selectedCustomer) {
    filterFacilities(selectedCustomer);
}">
    <flux:modal name="upload-contract" class="md:max-w-3xl">
        <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">Upload Contract</flux:heading>
                <flux:text class="mt-2">
                    Contract upload accepts pdf format.
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

            {{-- Facility Dropdown (Filtered) --}}
            <flux:field>
                <flux:label>Select Facility</flux:label>
                <flux:select name="facility_id" placeholder="— Select facility —">

                    <template x-if="!selectedCustomer">
                        <option value="">Select customer first</option>
                    </template>
                    <template x-if="selectedCustomer && filteredFacilities.length === 0">
                        <option value="">No facilities available for this customer</option>
                    </template>
                    <template x-if="selectedCustomer && filteredFacilities.length > 0">
                        <option value="">— Select facility —</option>
                    </template>

                    {{-- Dynamically filtered facilities --}}
                    <template x-for="facility in filteredFacilities" :key="facility.id">
                        <option :value="facility.id" x-text="`${facility.name} (SEIN: ${facility.sein})`"
                            :selected="'{{ old('facility_id') }}' == facility.id">
                        </option>
                    </template>
                </flux:select>
                @error('facility_id')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            {{-- two-column body --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                <flux:field>
                    <flux:label badge="Required">Contract Start Period</flux:label>
                    <flux:input name="contract_start" type="date" value="{{ old('contract_start') }}" required />
                    @error('contract_start')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Contract End Period</flux:label>
                    <flux:input name="contract_end" type="date" value="{{ old('contract_end') }}" required />
                    @error('contract_end')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label badge="Required">Description</flux:label>
                    <flux:input name="description" value="{{ old('description') }}" placeholder="Enter description"
                        required />
                    @error('description')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:input type="file" name="document" label="Document" badge="Required" accept=".pdf"
                        required />
                    @error('document')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>
            </div>

            {{-- full-width footer with right-aligned button --}}
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <flux:button type="submit" variant="primary">
                    Upload Contract
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
