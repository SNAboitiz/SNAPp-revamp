<flux:modal name="delete-facility" class="min-w-[22rem]">
    <form method="POST" id="delete-facility-form" class="space-y-6">
        @csrf
        @method('DELETE')

        <div>
            <flux:heading size="lg">Delete Facility?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete <strong id="delete-facility-name">this facility</strong>.</p>
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
        const baseUrl = "{{ url('admin/facilities') }}";
        const form = document.getElementById('delete-facility-form');
        const nameEl = document.getElementById('delete-facility-name');
        const deleteBtn = document.getElementById('confirm-delete-button');

        if (!form) return;

        // Set up delete trigger
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-facility-id]');
            if (!trigger) return;

            const id = trigger.dataset.facilityId;
            const name = trigger.dataset.facilityName || 'this facility';

            form.action = `${baseUrl}/${id}`;
            if (nameEl) nameEl.textContent = name;

            // Reset button state each time modal opens
            deleteBtn.disabled = false;
            deleteBtn.textContent = 'Delete';
        }, true);

        // Prevent double-clicks
        form.addEventListener('submit', (e) => {
            // Disable button immediately
            deleteBtn.disabled = true;
            deleteBtn.textContent = 'Deleting...';
        });
    })();
</script>