<flux:modal name="delete-permission" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete permission?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete this permission.</p>
                <p>This action cannot be reversed.</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" id="delete-permission" data-id="{{ $permission->id }}">Delete</flux:button>
        </div>
    </div>
</flux:modal>

<script>
    document.getElementById('delete-permission').addEventListener('click', function() {
        const permissionId = this.getAttribute('data-id');
        const deleteBtn = this;

        deleteBtn.disabled = true;
        deleteBtn.innerText = 'Deleting...';

        fetch(`/admin/permissions/${permissionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    // Redirect to the permissions listing page or the updated page
                    window.location.href = '{{ route('role.permission.list') }}';
                } else {
                    return response.json().then(data => {
                        throw data;
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                deleteBtn.disabled = false;
                deleteBtn.innerText = 'Delete';
                alert('Something went wrong. Try again.');
            });
    });
</script>
