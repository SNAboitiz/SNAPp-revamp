<flux:modal name="upload-bills" class="md:max-w-3xl">
    <form action="{{ route('bills.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="create-form">
        @csrf

        <div>
            <flux:heading size="lg">Upload Bill</flux:heading>
            <flux:text class="mt-2">
                Bill upload accepts pdf, doc, and docx formats.
            </flux:text>
        </div>

        <flux:field>
            <flux:label badge="Required">Select Customer</flux:label>
            <flux:select
                name="customer_id"
                placeholder="— Select customer —"
                required
                :error="$errors->first('customer_id')">
                @foreach ($customers as $customer)
                    <option
                        value="{{ $customer->id }}"
                        class="text-black"
                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->account_name }} ({{ $customer->short_name }})
                    </option>
                @endforeach
            </flux:select>
            @error('customer_id')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </flux:field>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            <flux:field>
                <flux:label badge="Required">Contract Start Period</flux:label>
                <flux:input name="billing_start_date" type="date" value="{{ old('billing_start_date') }}" />
                @error('billing_start_date')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label badge="Required">Contract End Period</flux:label>
                <flux:input name="billing_end_date" type="date" value="{{ old('billing_end_date') }}" />
                @error('billing_end_date')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label badge="Required">Bill Number</flux:label>
                <flux:input name="bill_number" value="{{ old('bill_number') }}" placeholder="Enter unique bill number" />
                @error('bill_number')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:input type="file" name="file_path" label="Document" badge="Required" accept=".pdf,.doc,.docx" />
                @error('file_path')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <flux:button type="submit" variant="primary">
                Upload Bill
            </flux:button>
        </div>
    </form>
</flux:modal>