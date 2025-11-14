<flux:modal name="confirm-delete" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete customer account?</flux:heading>
            <flux:text class="mt-2">
                <p>You're about to delete this customer account.</p>
                <p>This action cannot be reversed.</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            @if (isset($user))
                <flux:button variant="danger" id="delete-button" data-id="{{ $user->id }}">Delete</flux:button>
            @endif
        </div>
    </div>
</flux:modal>

<script>
    document.getElementById('delete-button').addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const deleteBtn = this;

        deleteBtn.disabled = true;
        deleteBtn.innerText = 'Deleting...';

        fetch(`/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    // Optional: remove user row from table or redirect
                    window.location.href = '{{ route('users.index') }}';
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
