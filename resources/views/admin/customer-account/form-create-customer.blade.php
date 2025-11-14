<div x-data="{}" x-init="@if (session('show_modal') === 'customer-modal') $nextTick(() => $flux.modal('customer-modal').show()) @endif">
    <flux:modal name="customer-modal" class="md:w-96">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Customer Account
                </flux:heading>
                <flux:text class="mt-2">
                    Fill in the required user details and choose an existing profile.
                </flux:text>
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

            <flux:field label="Assign Profile" for="customer_id" required>
                <flux:select id="customer_id" name="customer_id" placeholder="— Select account —" required
                    :error="$errors->first('customer_id')">
                    @foreach ($profiles as $profile)
                        <option value="{{ $profile->customer_id }}" class="text-black" @selected(old('customer_id') == $profile->customer_id)>
                            {{ $profile->account_name }} ({{ $profile->short_name }})
                        </option>
                    @endforeach
                </flux:select>
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
        document.getElementById('create-button').addEventListener('click', function() {
            this.disabled = true;
            this.innerText = 'Creating…';
            document.getElementById('create-form').submit();
        });
    </script>
</div>
