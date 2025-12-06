<div x-data="{}" x-init="@if (session('show_modal') === 'create-admin') $nextTick(() => $flux.modal('create-admin').show()) @endif">
    <flux:modal name="create-admin" class="md:w-96">
        <form action="{{ route('admin.users.store-admin') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Admin Account
                </flux:heading>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" placeholder="Enter admin name" />
                @error('name')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input name="email" type="email" value="{{ old('email') }}" placeholder="Enter admin email" />
                @error('email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <!-- Assign Customer -->
            <flux:field>
                <flux:label>Customer</flux:label>
                <flux:select id="customer_id" name="customer_id" placeholder="— Select account —"
                    :error="$errors->first('customer_id')">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" class="text-black" @selected(old('customer_id') == $customer->id)>
                            {{ $customer->account_name }} ({{ $customer->short_name }})
                        </option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Assign Facility -->
            <flux:field>
                <flux:label>Facility</flux:label>
                <flux:select id="facility_id" name="facility_id" placeholder="— Select facility (optional) —">
                    <option value="">— No facility —</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}" data-customer-id="{{ $facility->customer_id }}"
                            @selected(old('facility_id') == $facility->id)>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </flux:select>
                @error('facility_id')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" id="create-button">
                    Create Account
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        // Filter facilities based on selected customer
        const customerSelect = document.getElementById('customer_id');
        const facilitySelect = document.getElementById('facility_id');
        const facilityOptions = Array.from(facilitySelect.querySelectorAll('option'));

        function filterFacilities() {
            const selectedCustomerId = customerSelect.value;

            // Reset facility selection
            facilitySelect.value = '';

            // Show/hide options based on customer
            facilityOptions.forEach(option => {
                if (!option.value) {
                    // Keep the placeholder visible
                    option.style.display = '';
                    return;
                }

                const facilityCustomerId = option.dataset.customerId;

                if (selectedCustomerId && facilityCustomerId !== selectedCustomerId) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        }

        // Filter on customer change
        customerSelect.addEventListener('change', filterFacilities);

        // Filter on page load if customer is pre-selected
        if (customerSelect.value) {
            filterFacilities();
        }

        document.getElementById('create-form').addEventListener('submit', function(e) {
            const button = document.getElementById('create-button');
            button.disabled = true;
            button.innerText = 'Creating…';
        });
    </script>
</div>
