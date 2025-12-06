<div x-data="{}" x-init="@if (session('show_modal') === 'edit-accountexecutive-modal') $nextTick(() => $flux.modal('edit-accountexecutive-modal').show()) @endif">
    <flux:modal name="edit-accountexecutive-modal" class="md:w-96">

        <form data-base-action="{{ route('admin.users.update-account-executive', ['user' => ':user_id']) }}"
            method="POST" class="space-y-6" id="edit-ae-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" value="">
            <div>
                <flux:heading size="lg">
                    Edit Account Executive Account
                </flux:heading>
            </div>
            <!-- Add 'edit_' prefix to all fields -->
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input name="edit_name" value="{{ old('edit_name', '') }}"
                    placeholder="Enter Account Executive name" />
                @error('edit_name')
                    <!-- Update error key -->
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input name="edit_email" value="{{ old('edit_email', '') }}"
                    placeholder="Enter Account Executive email" />
                @error('edit_email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Customer</flux:label>
                <flux:select id="edit_customer_id" name="edit_customer_id" placeholder="— Select customer —"
                    :error="$errors->first('edit_customer_id')">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" class="text-black" @selected(old('edit_customer_id', $existingCustomerId ?? '') == $customer->id)>
                            {{ $customer->account_name }} ({{ $customer->short_name }})
                        </option>
                    @endforeach
                </flux:select>
            </flux:field>

            {{-- Facility Field - Updated to be optional --}}
            <flux:field>
                <flux:label>Facility</flux:label>
                <flux:select id="edit_facility_id" name="edit_facility_id" placeholder="— Select facility (optional) —"
                    :error="$errors->first('edit_facility_id')">
                    <option value="">— No facility —</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}" class="text-black"
                            data-customer-id="{{ $facility->customer_id }}" @selected(old('edit_facility_id', $existingFacilityId ?? '') == $facility->id)>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </flux:select>
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" id="save-button">
                    Save Changes
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>


<script>
    document.addEventListener('click', function(event) {
        const button = event.target.closest('.flux-btn-info');
        if (!button) return;

        const form = document.getElementById('edit-ae-form');
        const ds = button.dataset;
        form.action = form.dataset.baseAction.replace(':user_id', ds.id);

        const set = (name, val) => {
            const el = form.querySelector(`[name="${name}"]`);
            if (el) el.value = val || '';
        };

        set('edit_name', ds.name);
        set('edit_email', ds.email);
        set('edit_customer_id', ds.customerId);
        set('edit_facility_id', ds.facilityId);

        setTimeout(filterEditFacilities, 10);
    });

    const editCustomerSelect = document.getElementById('edit_customer_id');
    const editFacilitySelect = document.getElementById('edit_facility_id');

    if (editCustomerSelect && editFacilitySelect) {
        const editFacilityOptions = Array.from(editFacilitySelect.querySelectorAll('option'));

        function filterEditFacilities() {
            const selectedCustomerId = editCustomerSelect.value;
            const selectedFacilityId = editFacilitySelect.value;

            editFacilityOptions.forEach(option => {
                if (!option.value) {
                    option.style.display = '';
                    return;
                }

                const facilityCustomerId = option.dataset.customerId;

                if (selectedCustomerId && facilityCustomerId && facilityCustomerId !== selectedCustomerId) {
                    option.style.display = 'none';

                    // Clear selection if facility doesn't belong to new customer
                    if (option.value === selectedFacilityId) {
                        editFacilitySelect.value = '';
                    }
                } else {
                    option.style.display = '';
                }
            });
        }

        // Run filtering when customer changes
        editCustomerSelect.addEventListener('change', filterEditFacilities);
    }

    document.getElementById('save-button').addEventListener('click', function(e) {
        e.preventDefault();
        this.disabled = true;
        this.innerText = 'Saving…';

        document.getElementById('edit-ae-form').submit();
    });
</script>
