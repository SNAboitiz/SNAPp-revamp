<div x-show="showPdfModal" x-transition
    class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-2 sm:p-4" style="display: none;"
    role="dialog">
    <div @click.outside="showPdfModal = false"
        class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl w-full max-w-[95vw] h-[95vh] flex flex-col md:flex-row">

        <!-- Left Panel: PDF Viewer -->
        <div class="flex-grow h-2/3 md:h-full bg-gray-500 rounded-t-xl md:rounded-l-xl md:rounded-r-none">
            <template x-if="pdfUrl">
                <embed :src="pdfUrl + '#toolbar=1&navpanes=0'" type="application/pdf"
                    class="w-full h-full rounded-t-xl md:rounded-l-xl md:rounded-r-none" />
            </template>
        </div>

        <!-- Right Panel: Details & Actions -->
        <div
            class="w-full md:max-w-sm flex-shrink-0 p-4 sm:p-6 flex flex-col border-t md:border-t-0 md:border-l border-gray-200 h-1/3 md:h-full">
            <div class="flex-shrink-0 flex items-start justify-between">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="selectedContract.contractName"></h2>
                <button @click="showPdfModal = false"
                    class="-mt-1 -mr-2 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-auto pt-4 flex-shrink-0">
                <a x-show="pdfUrl" :href="pdfUrl" download class="w-full">
                    <flux:button class="w-full">Download Contract</flux:button>
                </a>
            </div>
        </div>
    </div>
</div>
