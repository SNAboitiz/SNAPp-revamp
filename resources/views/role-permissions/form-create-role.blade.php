<flux:modal name="create-role" class="md:w-96">
    <div class="space-y-6">
        <form action="{{ route('role.store') }}" method="POST" id="role-form">
            @csrf
            <div>
                <flux:heading size="lg">Create Role</flux:heading>
            </div>

            <flux:field>
                <flux:input label="Name" placeholder="Role name" name="title" value="{{ old('title') }}" />
            </flux:field>

            <div class="flex">
                <!-- Add margin-top or margin-bottom to create space -->
                <flux:button type="submit" variant="primary" class="mt-4" onclick="console.log('Form submitted')">
                    Save changes
                </flux:button>
            </div>
        </form>
    </div>
</flux:modal>
