<x-layouts.app>
    {{-- This Alpine.js component controls the modal on this page --}}
    <div x-data="{
        showPdfModal: false,
        pdfUrl: '',
        selectedContract: {},
        openContractViewer(element) {
            this.selectedContract = element.dataset;
            this.pdfUrl = element.dataset.gcsPdfUrl;
            if (this.pdfUrl) {
                this.showPdfModal = true;
            }
        }
    }" @keydown.escape.window="showPdfModal = false">

        <div class="p-4 sm:p-6 bg-white rounded-xl shadow-md">

            @can('can upload contracts')
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <flux:modal.trigger name="upload-contract">
                            <flux:button variant="primary" icon="arrow-up-tray">
                                Upload Contract
                            </flux:button>
                        </flux:modal.trigger>

                    </div>
                </div>
            @endcan
            {{-- Contracts Table --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                @include('contracts.contract-list-table', ['contracts' => $contracts])
            </div>
        </div>
    </div>
    @include('contracts.form-upload-contract')
</x-layouts.app>
