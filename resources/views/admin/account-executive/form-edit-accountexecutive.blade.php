<div x-data="{}" x-init="
    @if (session('show_modal') === 'edit-accountexecutive-modal')
        $nextTick(() => $flux.modal('edit-accountexecutive-modal').show())
    @endif
    ">
    <flux:modal name="edit-accountexecutive-modal"
        class="md:w-96">

        <form
            data-base-action="{{ route('admin.users.update-account-executive', 
                    ['user' => ':user_id']) }}"
            method="POST"
            id="edit-customer-form"
            class="space-y-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_id" value="">
            <div>
                <flux:heading size="lg">
                    Edit Account Executive Account
                </flux:heading>

                <flux:text class="mt-2">
                    Update the Account Executive details below.
                </flux:text>
            </div>
            <!-- Add 'edit_' prefix to all fields -->
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input
                    name="edit_name"
                    value="{{ old('edit_name', '') }}"
                    placeholder="Enter Account Executive name" />
                @error('edit_name') <!-- Update error key -->
                <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input
                    name="edit_email"
                    value="{{ old('edit_email', '') }}"
                    placeholder="Enter Account Executive email" />
                @error('edit_email')
                <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Customer</flux:label>
                <flux:select
                    id="edit_customer_id"
                    name="edit_customer_id"
                    placeholder="— Select account —"
                    required
                    :error="$errors->first('edit_customer_id')">
                    @foreach ($customers as $customer)
                    <option value="{{ $customer->customer_id }}"
                        class="text-black"
                        @selected(old('edit_customer_id')==$customer->customer_id)>
                        {{ $customer->account_name }} ({{ $customer->short_name }})
                    </option>
                    @endforeach
                </flux:select>
            </flux:field>


            <div class="flex">
                <flux:spacer />
                <flux:button
                    type="submit"
                    variant="primary">Save Changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>


<script>
    document.querySelectorAll('.flux-btn-info').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const userName = this.getAttribute('data-name');
            const userEmail = this.getAttribute('data-email');
            const customerId = this.getAttribute('data-customer-id');

            // Update form action
            const form = document.getElementById('edit-customer-form');
            const baseAction = form.getAttribute('data-base-action');
            form.action = baseAction.replace(':user_id', userId);

            // Update fields with new names
            document.querySelector('input[name="user_id"]').value = userId;
            document.querySelector('input[name="edit_name"]').value = userName;
            document.querySelector('input[name="edit_email"]').value = userEmail;
            document.querySelector('select[name="edit_customer_id"]').value = customerId;
        });
    });
</script>