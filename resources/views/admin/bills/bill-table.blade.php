<div x-data="{
    showBillModal: false,
    billUrl: '',
    selectedContract: {},
    openContractViewer(el) {
        this.selectedContract = el.dataset;
        this.billUrl      = el.dataset.gcsPdfUrl;
        this.showBillModal = true;
    }
}" @keydown.escape.window="showBillModal = false">

    {{-- BILL TABLE (identical structure to your contracts table) --}}
    <div class="min-w-full divide-y divide-gray-200">
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Billing Period</th>
                    <th>Bill Number</th>
                    <th>Uploaded At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $item)
                <tr
                    @if($item['gcsPdfUrl'])
                    class="cursor-pointer hover:bg-gray-100 transition"
                    @click="openContractViewer($el)"
                    @endif
                    data-customer-name="{{ $item['accountName'] }}"
                    data-billing-period="{{ $item['billingPeriod'] }}"
                    data-bill-number="{{ $item['billNumber'] }}"
                    data-uploaded-at="{{ $item['uploadedAt'] }}"
                    data-gcs-pdf-url="{{ $item['gcsPdfUrl'] }}">
                    <td>{{ $item['accountName'] }}</td>
                    <td>{{ $item['billingPeriod'] }}</td>
                    <td>{{ $item['billNumber'] }}</td>
                    <td>{{ $item['uploadedAt'] }}</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- PAGINATION --}}
    @if($bills->hasPages())
    <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200">
        {{ $bills->links() }}
    </div>
    @endif
    @include('admin.bills.form-view-pdf')

</div>