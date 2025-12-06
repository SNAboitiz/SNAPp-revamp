<div x-data="{}" x-init="@if (session('show_modal') === 'create-customer') $nextTick(() => $flux.modal('create-customer').show()) @endif">
    <flux:modal name="create-customer" class="md:w-96">
        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Customer
                </flux:heading>
            </div>

            <flux:field>
                <flux:label badge="Required">Account Name</flux:label>
                <flux:input name="account_name" value="{{ old('account_name') }}" placeholder="Enter account name" />
                @error('account_name')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Short Name</flux:label>
                <flux:input name="short_name" value="{{ old('short_name') }}" placeholder="Enter short name" />
                @error('short_name')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Oracle Customer ID</flux:label>
                <flux:input name="customer_number" value="{{ old('customer_number') }}"
                    placeholder="Enter oracle customer ID" />
                @error('customer_number')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>


            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" id="create-button">
                    Create
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        document.getElementById('create-button').addEventListener('click', function() {
            this.disabled = true;
            this.innerText = 'Creating…';
            document.getElementById('create-form').submit();
        });
    </script>
</div>
