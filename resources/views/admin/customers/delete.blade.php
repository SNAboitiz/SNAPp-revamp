<flux:modal name="delete-customer" class="min-w-[22rem]">
    <form method="POST" id="delete-customer-form" class="space-y-6">
        @csrf
        @method('DELETE')

        <div>
            <flux:heading size="lg">Delete Customer Account?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete <strong id="delete-customer-name">this customer</strong>.</p>
                <p>This action cannot be reversed.</p>
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button 
                type="submit"
                variant="danger"
                id="confirm-delete-button">
                Delete
            </flux:button>
        </div>
    </form>
</flux:modal>

<script>
(() => {
    const baseUrl = "{{ url('admin/customers') }}";
    const form = document.getElementById('delete-customer-form');
    const nameEl = document.getElementById('delete-customer-name');
    const deleteBtn = document.getElementById('confirm-delete-button');

    if (!form) return;

    // 🔹 Set up delete trigger
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-customer-id]');
        if (!trigger) return;

        const id = trigger.dataset.customerId;
        const name = trigger.dataset.customerName || 'this customer';

        form.action = `${baseUrl}/${id}`;
        if (nameEl) nameEl.textContent = name;

        // Reset button state each time modal opens
        deleteBtn.disabled = false;
        deleteBtn.textContent = 'Delete';
    }, true);

    // 🔹 Prevent double-clicks
    form.addEventListener('submit', (e) => {
        // Disable button immediately
        deleteBtn.disabled = true;
        deleteBtn.textContent = 'Deleting...';
        
        // Optional: if delete happens via full page reload, no need to re-enable
        // If handled by AJAX, re-enable manually after success/failure
    });
})();
</script>
