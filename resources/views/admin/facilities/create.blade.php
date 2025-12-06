<div x-data="{}" x-init="@if (session('show_modal') === 'facility-modal') $nextTick(() => $flux.modal('facility-modal').show()) @endif">
    <flux:modal name="facility-modal" class="md:w-96">
        <form action="{{ route('facilities.store') }}" method="POST" class="space-y-6" id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">
                    Create New Facility
                </flux:heading>
            </div>

            <flux:field>
                <flux:label badge="Required">Facility Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" placeholder="Enter facility name" />
                @error('name')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Short Name</flux:label>
                <flux:input name="sein" value="{{ old('sein') }}" placeholder="Enter sein" />
                @error('sein')
                    <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Select Customer</flux:label>
                <flux:select name="customer_id" placeholder="— Select customer —" required
                    :error="$errors->first('customer_id')">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" class="text-black"
                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->account_name }} ({{ $customer->short_name }})
                        </option>
                    @endforeach
                </flux:select>
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
