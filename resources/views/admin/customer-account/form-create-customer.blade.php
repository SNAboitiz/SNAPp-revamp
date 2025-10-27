<div
    x-data="{}"
    x-init="
    @if (session('show_modal') === 'customer-modal')
        $nextTick(() => $flux.modal('customer-modal').show())
    @endif
">
    <flux:modal
        name="customer-modal"
        class="md:w-96">
        <form
            action="{{ route('users.store') }}"
            method="POST"
            class="space-y-6"
            id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Customer Account
                </flux:heading>

            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter customer name" />
                @error('name')
                <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
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
                    required
                    :error="$errors->first('customer_id')">
                    @foreach ($customers as $customer)
                    <option
                        value="{{ $customer->id }}"
                        class="text-black"
                        @selected(old('customer_id')==$customer->id)>
                        {{ $customer->account_name }} ({{ $customer->short_name ?? '—' }})
                    </option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Assign Facility -->
            <flux:field label="Assign Facility" for="facility_id">
                <flux:label>Facility</flux:label>

                <flux:select
                    id="facility_id"
                    name="facility_id"
                    placeholder="— Select facility —"
                    :error="$errors->first('facility_id')">
                    @foreach ($facilities as $facility)
                    <option
                        value="{{ $facility->id }}"
                        class="text-black"
                        @selected(old('facility_id')==$facility->id)>
                        {{ $facility->name }}
                    </option>
                    @endforeach
                </flux:select>
            </flux:field>



            <div class="flex">
                <flux:spacer />
                <flux:button
                    type="submit"
                    variant="primary"
                    id="create-button">
                    Create Account
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