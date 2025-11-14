<div x-data x-init="@if ($errors->any()) $nextTick(() => $flux.modal('customer-modal').show()) @endif">
    <flux:modal name="customer-modal" class="md:w-96" :dismissible="false" x-data
        x-on:close="$el.querySelector('form').reset()">

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Customer Account
                </flux:heading>

                <flux:text class="mt-2">
                    Fill in the details below to create a new customer account.
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" placeholder="Enter customer name" />
                @error('name')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input name="email" type="email" value="{{ old('email') }}"
                    placeholder="Enter customer email" />
                @error('email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Customer ID</flux:label>
                <flux:input name="customer_id" value="{{ old('customer_id') }}" placeholder="Enter customer ID" />
                @error('customer_id')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">
                    Create Account
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
