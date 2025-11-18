<flux:modal name="upload-2307" class="md:max-w-3xl">
    <form
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
        id="upload-2307-form">
        @csrf

        <div>
            <flux:heading size="lg">Upload 2307 Form</flux:heading>
            <flux:text class="mt-2">
                Upload 2307 form for Payment Reference:
                <span id="modal-payment-ref" class="font-semibold"></span>
            </flux:text>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="document_number" id="document_number">
        <input type="hidden" name="customer_id" id="customer_id">
        <input type="hidden" name="facility_id" id="facility_id">

        <flux:field class="md:col-span-2">
            <flux:input
                type="file"
                name="file"
                label="2307 Document File"
                badge="Required"
                accept=".pdf"
                required />
            @error('file')
            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </flux:field>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <flux:button
                type="button"
                class="mr-3 bg-gray-200 text-gray-800 hover:bg-gray-300"
                flux:click="$flux.modal('upload-2307').hide()">
                Cancel
            </flux:button>

            <flux:button type="submit" color="primary">
                Upload 2307
            </flux:button>
        </div>
    </form>
</flux:modal>

<script>
    // Called when the "Upload 2307" button in the table is clicked
    function prepare2307Upload(button) {
        const ds = button.dataset;
        const form = document.getElementById('upload-2307-form');

        // Replace placeholders dynamically
        form.action = `/admin/customers/${ds.customerId}/facilities/${ds.facilityId}/tax-documents`;

        // Fill hidden values
        document.getElementById('document_number').value = ds.documentNumber;
        document.getElementById('customer_id').value = ds.customerId;
        document.getElementById('facility_id').value = ds.facilityId;
        document.getElementById('modal-payment-ref').textContent = ds.documentNumber;
    }

    // Reset form after modal closes
    document.addEventListener('flux-modal-hide', function (event) {
        if (event.detail.name === 'upload-2307') {
            const form = document.getElementById('upload-2307-form');
            form.reset();
        }
    });
</script>
