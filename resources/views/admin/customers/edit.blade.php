<flux:modal name="edit-customer" class="md:w-96">
    <form
        data-base-action="{{ route('customers.update', ['customer' => ':customer_id']) }}"
        method="POST"
        id="edit-customer"
        class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <flux:heading size="lg">
                Edit Customer
            </flux:heading>
        </div>

        <flux:field>
            <flux:label badge="Required">Customer Name</flux:label>
            <flux:input
                name="edit_account_name"
                placeholder="Enter account name" />
            @error('edit_account_name')
            <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </flux:field>

        <flux:field>
            <flux:label badge="Required">Short Name</flux:label>
            <flux:input
                name="edit_short_name"
                placeholder="Enter short name" />
            @error('edit_short_name')
            <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </flux:field>

        <flux:field>
            <flux:label badge="Required">Oracle Customer ID</flux:label>
            <flux:input
                name="edit_customer_number"
                placeholder="Enter oracle customer ID" />
            @error('edit_customer_number')
            <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </flux:field>

        <!-- Profile Management Section -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Profile Management</h3>
            <p class="text-sm text-blue-800 mb-4">Manage profiles for this customer and its facilities.</p>
            <a href="#" id="profile-link" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <span>Create/Edit Profile</span>
            </a>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button
                type="submit"
                variant="primary"
                id="save-button">
                Save Changes
            </flux:button>
        </div>
    </form>
</flux:modal>

<script>
    document.addEventListener('click', function(event) {
        const button = event.target.closest('.flux-btn-info');
        if (!button) return;

        const form = document.getElementById('edit-customer');
        const ds = button.dataset;
        form.action = form.dataset.baseAction.replace(':customer_id', ds.id);

        // Fill form fields with existing values
        form.querySelector('[name="edit_account_name"]').value = ds.accountName || '';
        form.querySelector('[name="edit_short_name"]').value = ds.shortName || '';
        form.querySelector('[name="edit_customer_number"]').value = ds.customerNumber || '';

        // Show modal
        $flux.modal('edit-customer').show();
    });
    // Update profile management link
    const profileLink = document.getElementById('profile-link');
    profileLink.href = `{{ route('admin.profiles.list') }}`;
    document.getElementById('save-button').addEventListener('click', function(e) {
        e.preventDefault();
        this.disabled = true;
        this.innerText = 'Saving…';
        document.getElementById('edit-customer').submit();
    });
</script>