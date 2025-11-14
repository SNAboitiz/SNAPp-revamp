<div x-data="{}" x-init="@if (session('show_modal') === 'create-admin') $nextTick(() => $flux.modal('create-admin').show()) @endif">
    <flux:modal name="create-admin" class="md:w-96">
        <form action="{{ route('admin.users.store-admin') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Admin Account
                </flux:heading>

                <flux:text class="mt-2">
                    Fill in the details below to create a new customer account.
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" placeholder="Enter admin name" />
                @error('name')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Email</flux:label>
                <flux:input name="email" type="email" value="{{ old('email') }}" placeholder="Enter admin email" />
                @error('email')
                    <p class="mt-2 text-red-500 dark:text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
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
                <flux:button type="button" id="create-button" variant="primary">
                    Create Account
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        document.getElementById('create-button').addEventListener('click', function() {
            const createBtn = this;
            const form = document.getElementById('create-form');

            createBtn.disabled = true;
            createBtn.innerText = 'Creating Account...';

            form.submit();
        });
    </script>
</div>
