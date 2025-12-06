<flux:modal name="upload-2307" class="md:max-w-3xl">
    <form method="POST" enctype="multipart/form-data" id="upload-2307-form">
        @csrf

        <div>
            <flux:heading size="lg">Upload 2307 Document</flux:heading>
            <flux:text class="mt-2">
                Upload 2307 for Payment Reference:
                <span id="modal-document-number" class="font-semibold"></span>
            </flux:text>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="customer_id" id="tax_customer_id">
        <input type="hidden" name="facility_id" id="tax_facility_id">
        <input type="hidden" name="document_number" id="tax_document_number">

        <flux:field>
            <flux:input type="file" name="file" label="2307 Document" badge="Required" accept=".pdf,.doc,.docx"
                required />
            @error('file')
                <p class="mt-2 text-xs text-red-5 00">{{ $message }}</p>
            @enderror
        </flux:field>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <flux:button type="button" class="mr-3 bg-gray-200 text-gray-800 hover:bg-gray-300"
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
    function prepare2307Upload(button) {
        const ds = button.dataset;
        const form = document.getElementById('upload-2307-form');

        console.log('Customer ID:', ds.customerId);
        console.log('Facility ID:', ds.facilityId);
        console.log('Document Number:', ds.documentNumber);

        // Build route - if no facility, don't include it in the URL at all
        const customerId = ds.customerId;
        const facilityId = ds.facilityId && ds.facilityId !== 'null' && ds.facilityId !== '' ? ds.facilityId : null;

        let route;
        if (facilityId) {
            route = `/admin/customers/${customerId}/tax-documents/${facilityId}`;
        } else {
            route = `/admin/customers/${customerId}/tax-documents`;
        }

        console.log('Final route:', route);
        form.action = route;

        // Fill hidden fields
        document.getElementById('tax_customer_id').value = customerId;
        document.getElementById('tax_facility_id').value = facilityId || '';
        document.getElementById('tax_document_number').value = ds.documentNumber;
        document.getElementById('modal-document-number').textContent = ds.documentNumber;
    }

    // Reset form after modal closes
    document.addEventListener('flux-modal-hide', function(event) {
        if (event.detail.name === 'upload-2307') {
            document.getElementById('upload-2307-form').reset();
        }
    });
</script>
