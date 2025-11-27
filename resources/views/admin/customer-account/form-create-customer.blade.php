<div x-data="{}" x-init="@if (session('show_modal') === 'customer-modal') $nextTick(() => $flux.modal('customer-modal').show()) @endif">
    <flux:modal name="customer-modal" class="md:w-96">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Customer Account
                </flux:heading>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" placeholder="Enter customer name" />
                @error('name')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input name="email" type="email" value="{{ old('email') }}"
                    placeholder="Enter customer email" />
                @error('email')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <!-- Assign Customer -->
            <flux:field>
                <flux:label badge="Required">Customer</flux:label>
                <flux:select
                    id="customer_id"
                    name="customer_id"
                    placeholder="— Select customer —"
                    required>
                    @foreach ($customers as $customer)
                        <option
                            value="{{ $customer->id }}"
                            @selected(old('customer_id') == $customer->id)>
                            {{ $customer->account_name }} ({{ $customer->short_name ?? '—' }})
                        </option>
                    @endforeach
                </flux:select>
                @error('customer_id')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <!-- Assign Facility -->
            <flux:field>
                <flux:label>Facility</flux:label>
                <flux:select
                    id="facility_id"
                    name="facility_id"
                    placeholder="— Select facility (optional) —">
                    @foreach ($facilities as $facility)
                        <option
                            value="{{ $facility->id }}"
                            @selected(old('facility_id') == $facility->id)>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </flux:select>
                @error('facility_id')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" id="create-button">
                    Create Account
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        document.getElementById('create-form').addEventListener('submit', function(e) {
            const button = document.getElementById('create-button');
            button.disabled = true;
            button.innerText = 'Creating…';
        });
    </script>
</div>