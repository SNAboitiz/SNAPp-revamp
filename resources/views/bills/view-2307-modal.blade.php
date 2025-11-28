<div x-show="show2307Modal" x-transition
    class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-2 sm:p-4" 
    style="display: none;"
    role="dialog"
    x-data="{ show2307Modal: false, taxDocUrl: '', selectedTaxDoc: {} }">
    
    <div @click.outside="show2307Modal = false"
        class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl w-full max-w-[95vw] h-[95vh] flex flex-col md:flex-row">

        <!-- Left Panel: PDF Viewer -->
        <div class="flex-grow h-2/3 md:h-full bg-gray-500 rounded-t-xl md:rounded-l-xl md:rounded-r-none">
            <template x-if="taxDocUrl">
                <embed :src="taxDocUrl + '#toolbar=1&navpanes=0'" type="application/pdf"
                    class="w-full h-full rounded-t-xl md:rounded-l-xl md:rounded-r-none" />
            </template>
            <template x-if="!taxDocUrl">
                <div class="w-full h-full flex items-center justify-center text-white font-semibold">
                    PDF not available for this 2307 document.
                </div>
            </template>
        </div>

        <!-- Right Panel: Details & Actions -->
        <div class="w-full md:max-w-sm flex-shrink-0 p-4 sm:p-6 flex flex-col border-t md:border-t-0 md:border-l border-gray-200 dark:border-neutral-700 h-1/3 md:h-full">
            <div class="flex-shrink-0 flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">2307 Document</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        <strong>Payment Ref:</strong> 
                        <span x-text="selectedTaxDoc.documentNumber"></span>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <strong>Payment Date:</strong> 
                        <span x-text="selectedTaxDoc.paymentDate"></span>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <strong>Billing Period:</strong> 
                        <span x-text="selectedTaxDoc.billingPeriod"></span>
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <strong>Amount:</strong> 
                        <span x-text="selectedTaxDoc.amount"></span>
                    </p>
                </div>
                <button @click="show2307Modal = false"
                    class="-mt-1 -mr-2 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-auto pt-4 flex-shrink-0">
                <a x-show="taxDocUrl" :href="taxDocUrl" download class="w-full">
                    <flux:button class="w-full">Download 2307</flux:button>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function view2307(element) {
    const data = {
        url: element.dataset.fileUrl,
        documentNumber: element.dataset.documentNumber,
        paymentDate: element.dataset.paymentDate,
        billingPeriod: element.dataset.billingPeriod,
        amount: element.dataset.amount
    };

    Alpine.store('taxDoc', data);
    
    // Trigger modal
    const modalElement = document.querySelector('[x-data*="show2307Modal"]');
    if (modalElement) {
        modalElement.__x.$data.show2307Modal = true;
        modalElement.__x.$data.taxDocUrl = data.url;
        modalElement.__x.$data.selectedTaxDoc = data;
    }
}
</script>