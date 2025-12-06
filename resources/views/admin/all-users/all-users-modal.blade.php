{{-- This is your existing modal, now with the new checkbox added --}}
<flux:modal name="all-users-modal" class="md:w-96">
    <form action="{{ route('all-user-list.update', ['user' => ':user_id']) }}"
        data-base-action="{{ route('all-user-list.update', ['user' => ':user_id']) }}" method="POST" id="edit-user-form"
        class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden user_id field (from your code) -->
        <input type="hidden" name="user_id" value="">

        <div>
            <flux:heading size="lg">Edit Customer Account</flux:heading>
            <flux:text class="mt-2">Update the customer details below.</flux:text>
        </div>

        {{-- Your existing form fields remain unchanged --}}
        <flux:field>
            <flux:label>Name</flux:label>
            <flux:input name="name" value="{{ old('name', '') }}" placeholder="Enter customer name" />
        </flux:field>

        <flux:field>
            <flux:label>Email</flux:label>
            <flux:input name="email" value="{{ old('email', '') }}" placeholder="Enter customer email" />
        </flux:field>

        <!-- Assign Customer -->
        <flux:field>
            <flux:label>Customer</flux:label>
            <flux:select id="customer_id" name="customer_id" placeholder="— Select customer —">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                        {{ $customer->account_name }} ({{ $customer->short_name ?? '—' }})
                    </option>
                @endforeach
            </flux:select>
            @error('customer_id')
                <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
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

        <flux:field>
            <flux:label>Role</flux:label>
            <flux:select name="role" id="role-select" placeholder="Choose role...">
                @foreach ($roles as $role)
                    <flux:select.option value="{{ $role->name }}">
                        {{ $role->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Account Status</flux:label>
            <div class="flex items-center gap-3 mt-1">
                <flux:switch id="account-status-switch" />
                <span id="account-status-label" class="text-sm font-medium text-gray-700">Loading...</span>
            </div>
            <input type="hidden" name="active" id="active-value" value="">
            <flux:error name="active" />
        </flux:field>

        {{-- UPDATED CHECKBOX TEXT --}}
        <div class="form-check pt-2">
            <input class="form-check-input" type="checkbox" name="resend_welcome_email" id="resend_welcome_email_modal"
                value="1">
            <label class="form-check-label" for="resend_welcome_email_modal">
                <strong class="text-sm">Reset Password</strong>
                <br>
                <small class="text-xs text-gray-500">Check this to send a new password to the user's email.</small>
            </label>
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
        </div>
    </form>
</flux:modal>

<script>
    document.addEventListener('click', function(event) {
        const button = event.target.closest('.flux-btn-info');
        if (!button) return;

        const form = document.getElementById('edit-user-form');
        const ds = button.dataset;
        form.action = form.dataset.baseAction.replace(':user_id', ds.id);

        const set = (name, val) => {
            const el = form.querySelector(`[name="${name}"]`);
            if (el) el.value = val || '';
        };

        set('user_id', ds.id);
        set('name', ds.name);
        set('email', ds.email);
        set('customer_id', ds.customerId);
        set('facility_id', ds.facilityId);
        set('role', ds.role);

        const resendCheckbox = form.querySelector('[name="resend_welcome_email"]');
        if (resendCheckbox) {
            resendCheckbox.checked = false;
        }

        const activeValue = ds.active;
        let isActive = activeValue === '1';
        const label = document.getElementById('account-status-label');
        const hidden = document.getElementById('active-value');
        const switchContainer = document.getElementById('account-status-switch');

        label.textContent = isActive ? 'Active' : 'Inactive';
        hidden.value = isActive ? '1' : '0';

        const newSwitch = switchContainer.cloneNode(true);
        switchContainer.parentNode.replaceChild(newSwitch, switchContainer);

        newSwitch.addEventListener('click', () => {
            isActive = !isActive;
            label.textContent = isActive ? 'Active' : 'Inactive';
            hidden.value = isActive ? '1' : '0';
        });

        setTimeout(filterFacilities, 10);
    });

    // EXACT LOGIC FROM WORKING DOCUMENT 3
    const customerSelect = document.getElementById('customer_id');
    const facilitySelect = document.getElementById('facility_id');

    if (customerSelect && facilitySelect) {
        const facilityOptions = Array.from(facilitySelect.querySelectorAll('option'));

        function filterFacilities() {
            const selectedCustomerId = customerSelect.value;
            const selectedFacilityId = facilitySelect.value;

            facilityOptions.forEach(option => {
                if (!option.value) {
                    option.style.display = '';
                    return;
                }

                const facilityCustomerId = option.dataset.customerId;

                if (selectedCustomerId && facilityCustomerId && facilityCustomerId !== selectedCustomerId) {
                    option.style.display = 'none';

                    // Clear selection if facility doesn't belong to new customer
                    if (option.value === selectedFacilityId) {
                        facilitySelect.value = '';
                    }
                } else {
                    option.style.display = '';
                }
            });
        }

        // Run filtering when customer changes
        customerSelect.addEventListener('change', filterFacilities);
    }
</script>
