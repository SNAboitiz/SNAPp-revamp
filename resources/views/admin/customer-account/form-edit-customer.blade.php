<div
    x-data="{}"
    x-init="
    @if (session('show_modal') === 'edit-customer-modal')
        $nextTick(() => $flux.modal('edit-customer-modal').show())
    @endif
">
    <flux:modal
        name="edit-customer-modal"
        class="md:w-96">
        <form
            data-base-action="{{ route('users.update', ['user' => ':user_id']) }}"
            method="POST"
            id="edit-customer-form"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <flux:heading size="lg">
                    Edit Customer Account
                </flux:heading>
            </div>

            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input
                    name="edit_name"
                    placeholder="Enter customer name" />
                @error('edit_name')
                <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input
                    name="edit_email"
                    type="email"
                    placeholder="Enter customer email" />
                @error('edit_email')
                <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                @enderror
            </flux:field>

            {{-- Customer Field --}}
            <flux:field>
                <flux:label>Customer</flux:label>
                <flux:select
                    id="edit_customer_id"
                    name="edit_customer_id"
                    placeholder="— Select customer —"
                    required
                    :error="$errors->first('edit_customer_id')">
                    @foreach ($customers as $customer)
                    <option
                        value="{{ $customer->id }}"
                        class="text-black"
                        @selected(old('edit_customer_id', $existingCustomerId ?? '' )==$customer->id)>
                        {{ $customer->account_name }} ({{ $customer->short_name }})
                    </option>
                    @endforeach
                </flux:select>
            </flux:field>


            {{-- Facility Field --}}
            <flux:field>
                <flux:label>Facility</flux:label>
                <flux:select
                    id="edit_facility_id"
                    name="edit_facility_id"
                    placeholder="— Select facility —"
                    required
                    :error="$errors->first('edit_facility_id')">
                    @foreach ($facilities as $facility)
                    <option
                        value="{{ $facility->id }}"
                        class="text-black"
                        @selected(old('edit_facility_id', $existingFacilityId ?? '' )==$facility->id)>
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
                    id="save-button">
                    Save Changes
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <script>
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.flux-btn-info');
            if (!button) return;

            const form = document.getElementById('edit-customer-form');
            const ds = button.dataset;
            form.action = form.dataset.baseAction.replace(':user_id', ds.id);

            const set = (name, val) => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) el.value = val || '';
            };

            set('edit_name', ds.name);
            set('edit_email', ds.email);
            set('edit_customer_id', ds.customerId);
            set('edit_facility_id', ds.facilityId);

        });

        document.getElementById('save-button').addEventListener('click', function(e) {
            e.preventDefault();
            this.disabled = true;
            this.innerText = 'Saving…';

            document.getElementById('edit-customer-form').submit();
        });
    </script>
</div>