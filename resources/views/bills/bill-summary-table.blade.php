<div x-data="{
    showPdfModal: false,
    pdfUrl: '',
    selectedBill: {},
    openPdfViewer(element) {
        this.selectedBill = element.dataset;
        this.pdfUrl = element.dataset.gcsPdfUrl;
        this.showPdfModal = true;
    }
}" @keydown.escape.window="showPdfModal = false">

    <div class="min-w-full divide-y divide-gray-200">
        <table>
            <thead>
                <tr>
                    <th>Facility</th>
                    <th>Bill Number</th>
                    <th>Billing Period</th>
                    <th>Posting Date</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $item)
                    <tr @if ($item['gcsPdfUrl']) class="cursor-pointer hover:bg-gray-100 transition"
                    @click="openPdfViewer($event.currentTarget)"
                    @else
                    class="hover:bg-gray-100 transition" @endif
                        {{-- Data attributes are on the row, so the openPdfViewer function can access them --}} data-bill-number="{{ $item['Power Bill Number'] }}"
                        data-billing-period="{{ $item['Billing Period'] }}"
                        data-posting-date="{{ $item['Posting Date'] }}" data-status="{{ $item['Status'] }}"
                        data-total-amount="{{ str_replace(',', '', $item['Total Amount']) }}"
                        data-gcs-pdf-url="{{ $item['gcsPdfUrl'] ?? '' }}">

                        <td class="text-black">{{ $item['Facility'] }}</td>
                        <td class="text-black">{{ $item['Power Bill Number'] }}</td>
                        <td class="text-black">{{ $item['Billing Period'] }}</td>
                        <td class="text-black">{{ $item['Posting Date'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $item['Status'] === 'PAID' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item['Status'] }}
                            </span>
                        </td>
                        <td class="text-black">₱{{ $item['Total Amount'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            {{-- YOUR original button, now functional for a direct view/download --}}
                            @if ($item['gcsPdfUrl'])
                                <button type="button" @click.stop="window.open('{{ $item['gcsPdfUrl'] }}', '_blank')"
                                    title="View/Download Bill"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-[#1443e0] text-white hover:bg-[#0d3ab9] transition-colors">

                                    <flux:icon name="download" class="h-4 w-4" />
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No bills found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    @if (isset($bills) && $bills->hasPages())
        <div class="mt-4 px-4 py-3 bg-white border-t border-gray-200">
            {{ $bills->links() }}
        </div>
    @endif


    <div x-show="showPdfModal" x-transition
        class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-2 sm:p-4"
        style="display: none;" role="dialog" aria-modal="true">
        <div @click.outside="showPdfModal = false"
            class="bg-white rounded-xl shadow-xl w-full max-w-[95vw] h-[95vh] flex flex-col md:flex-row">

            <!-- Left Panel: PDF Viewer -->
            <div class="flex-grow h-2/3 md:h-full bg-gray-500 rounded-t-xl md:rounded-l-xl md:rounded-r-none">
                <template x-if="pdfUrl">
                    <embed :src="pdfUrl + '#toolbar=1&navpanes=0'" type="application/pdf"
                        class="w-full h-full rounded-t-xl md:rounded-l-xl md:rounded-r-none" />
                </template>
                <template x-if="!pdfUrl">
                    <div class="w-full h-full flex items-center justify-center text-center text-gray-500">
                        <p>No bill PDF attachment available.</p>
                    </div>
                </template>
            </div>

            <!-- Right Panel: Details & Actions -->
            <div
                class="w-full md:max-w-sm flex-shrink-0 p-4 sm:p-6 flex flex-col border-t md:border-t-0 md:border-l border-gray-200 h-1/3 md:h-full">
                <div class="flex-shrink-0 flex items-start justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Billing Details</h2>
                    <button @click="showPdfModal = false" class="-mt-1 -mr-2 p-2 rounded-full hover:bg-gray-200"
                        aria-label="Close modal">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-6 text-sm overflow-y-auto flex-grow">
                    <dl class="space-y-4">
                        <div>
                            <dt class="font-medium text-gray-500">Bill Number</dt>
                            <dd class="mt-1 text-gray-900 " x-text="selectedBill.billNumber"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Billing Period</dt>
                            <dd class="mt-1 text-gray-900 " x-text="selectedBill.billingPeriod"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Posting Date</dt>
                            <dd class="mt-1 text-gray-900 " x-text="selectedBill.postingDate"></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd class="mt-1"><span class="px-2 py-1 rounded-full text-xs"
                                    :class="{
                                        'bg-green-100 text-green-800': selectedBill
                                            .status === 'PAID',
                                        'bg-red-100 text-red-800': selectedBill
                                            .status !== 'PAID'
                                    }"
                                    x-text="selectedBill.status"></span></dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-xl font-semibold text-gray-900 ">₱<span
                                    x-text="Number(selectedBill.totalAmount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-auto pt-4 flex-shrink-0">
                    <a x-show="pdfUrl" :href="pdfUrl" download class="w-full">
                        <flux:button class="w-full">Download PDF</flux:button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
