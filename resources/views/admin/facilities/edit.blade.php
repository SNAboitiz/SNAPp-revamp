<flux:modal name="edit-facility" class="md:w-96">
    <form
        data-base-action="{{ route('facilities.update', ['facility' => ':facility_id']) }}"
        method="POST"
        id="edit-facility"
        class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <flux:heading size="lg">
                Edit Facility
            </flux:heading>
        </div>

        <flux:field>
            <flux:label>Facility Name</flux:label>
            <flux:input
                name="edit_name"
                placeholder="Enter facility name" />
            @error('edit_name')
            <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </flux:field>

        <flux:field>
            <flux:label>Short Name</flux:label>
            <flux:input
                name="edit_sein"
                placeholder="Enter sein" />
            @error('edit_sein')
            <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </flux:field>

        <flux:field>
            <flux:label badge="Required">Customer</flux:label>
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
        </flux:field>


        <div class="flex">
            <flux:spacer />
            <flux:button
                type="submit"
                variant="primary"
                id="save-button">
                Save Changes
            </flux:button>
        </div>
    </form>
</flux:modal>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.flux-btn-info').forEach(row => {
            row.addEventListener('click', function() {
                const form = document.getElementById('edit-facility');
                const ds = this.dataset;

                // safely set action URL
                form.action = form.dataset.baseAction.replace(':facility_id', ds.id);

                // fill form fields
                form.querySelector('[name="edit_name"]').value = ds.name || '';
                form.querySelector('[name="edit_sein"]').value = ds.sein || '';
                form.querySelector('[name="customer_id"]').value = ds.customerId || '';

                // open modal
                $flux.modal('edit-facility').show();
            });
        });

        // prevent double-submit
        const saveBtn = document.getElementById('save-button');
        const form = document.getElementById('edit-facility');
        form.addEventListener('submit', () => {
            saveBtn.disabled = true;
            saveBtn.innerText = 'Saving…';
        });
    });
</script>