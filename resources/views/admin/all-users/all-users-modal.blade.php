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

        <flux:field label="Assign customer" for="customer_id" required>
            <flux:select id="customer_id" name="customer_id" placeholder="— Select account —" required
                :error="$errors->first('customer_id')">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" class="text-black" @selected(old('customer_id') == $customer->id)>
                        {{ $customer->account_name }} ({{ $customer->short_name }})
                    </option>
                @endforeach
            </flux:select>
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

        {{-- ======================================================= --}}
        {{-- UPDATED CHECKBOX TEXT --}}
        {{-- ======================================================= --}}
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

{{-- This is your existing script, now with one line added to reset the checkbox --}}
<script>
    document.querySelectorAll('.flux-btn-info').forEach(button => {
        button.addEventListener('click', function() {
            // Gather data-attributes
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const userEmail = this.dataset.email;
            const customerId = this.dataset.customerId;
            const userRole = this.dataset.role;
            const active = this.dataset.active; // "1" or "0"

            // Populate form fields + action
            const form = document.getElementById('edit-user-form');
            const baseAction = form.dataset.baseAction;
            form.action = baseAction.replace(':user_id', userId);

            // This was a minor bug in your original script; it's better to select by name attribute
            form.querySelector('[name="user_id"]').value = userId;
            form.querySelector('[name="name"]').value = userName;
            form.querySelector('[name="email"]').value = userEmail;
            form.querySelector('[name="customer_id"]').value = customerId;
            form.querySelector('[name="role"]').value = userRole;

            // *** NEW LINE: This resets the checkbox every time the modal opens ***
            form.querySelector('[name="resend_welcome_email"]').checked = false;

            // Initialize state for the status switch
            let isActive = active === '1';
            const label = document.getElementById('account-status-label');
            const hidden = document.getElementById('active-value');
            const switchContainer = document.getElementById('account-status-switch');

            // Set initial UI for the switch
            label.textContent = isActive ? 'Active' : 'Inactive'; // Changed label to be more intuitive
            hidden.value = isActive ? '1' : '0';

            // Remove old handler if any to prevent stacking listeners
            const newSwitch = switchContainer.cloneNode(true);
            switchContainer.parentNode.replaceChild(newSwitch, switchContainer);

            // Toggle on click
            newSwitch.addEventListener('click', () => {
                isActive = !isActive;
                label.textContent = isActive ? 'Active' : 'Inactive';
                hidden.value = isActive ? '1' : '0';
            });

            // Show the modal
            $flux.modal('all-users-modal').show();
        });
    });
</script>
