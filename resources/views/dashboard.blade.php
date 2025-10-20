<x-layouts.app>
    <div class="w-full px-4 py-6 space-y-6" x-data="{
        activeAdvisory: null,
        showModal: false,
        moreAdvisories: {{ Js::from($moreAdvisories) }},
        loading: false,
        loadMoreUrl: '{{ route('dashboard.load-more') }}',
        async loadMore() {
            this.loading = true;
            try {
                const response = await fetch(`${this.loadMoreUrl}?skip=${this.moreAdvisories.length}`);
                const data = await response.json();
                this.moreAdvisories = [...this.moreAdvisories, ...data];
            } catch (error) {
                console.error('Error loading more advisories:', error);
            }
            this.loading = false;
        }
    }">

        <!-- Top Section: Chart and Advisories -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <!-- Chart (Left) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">At A Glance</h2>
                <div class="mt-6 flex-1">
                    <iframe src="{{ $lookerUrl }}" class="w-full h-[300px] rounded-xl border-none" frameborder="0"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>




            <!-- Advisories (Right) -->
            <div class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col">
                <h2 class="text-lg font-bold text-[#1443e0]">Latest Advisories</h2>

                {{-- This container will scroll if advisories exceed the max height --}}
                <div class="mt-4 space-y-4 overflow-y-auto max-h-[250px] pr-2">
                    <template x-for="(advisory, index) in moreAdvisories" :key="advisory.id">
                        <div @click="activeAdvisory = advisory; showModal = true"
                            class="cursor-pointer group hover:bg-blue-50 p-3 rounded-lg transition border border-gray-100">
                            <h4 class="text-md font-semibold text-[#1443e0] group-hover:text-[#0d3ab9]"
                                x-text="advisory.headline"></h4>
                            <p class="text-sm text-[#1443e0] mt-1 line-clamp-2" x-text="advisory.description"></p>
                            <div class="text-xs text-gray-500 mt-1"
                                x-text="new Date(advisory.created_at).toLocaleDateString()"></div>
                            <hr class="border-t border-gray-200 my-2">
                        </div>
                    </template>

                    <template x-if="moreAdvisories.length === 0">
                        <p class="text-sm text-gray-500">No advisories available.</p>
                    </template>
                </div>

                <button @click="loadMore()" :disabled="loading"
                    class="mt-6 flex items-center justify-center w-full py-2 border border-[#1443e0] rounded-lg text-sm font-medium text-[#1443e0] hover:bg-blue-50 transition">
                    <span x-text="loading ? 'Loading...' : 'Load More'" class="mr-2"></span>
                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Bottom Section: Billing and Contract Information -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Billing Statement Card (wider) -->
            <div class="col-span-1 md:col-span-3 bg-white rounded-2xl shadow p-6 flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-2">
                    <span
                        class="text-[10px] font-semibold leading-tight px-2 py-[1px] rounded-full
                        {{ $Status === 'PAID' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $Status }}
                    </span>
                </div>





                <div
                    class="flex flex-wrap md:flex-nowrap items-center justify-center md:justify-between gap-4 md:gap-0">
                    <!-- Total Amount -->
                    <div class="flex-1 text-center min-w-[120px]">
                        <span class="text-xs font-medium text-[#1443e0]">Total Amount</span>
                        <div class="text-2xl font-bold text-[#1443e0] mt-1 truncate">₱{{ $currentAmount }}</div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden md:block h-12 w-px bg-gray-300 mx-4"></div>

                    <!-- Due Date -->
                    <div class="flex-1 text-center min-w-[120px]">
                        <span class="text-xs font-medium text-[#1443e0]">Due Date</span>
                        <div class="text-2xl font-bold text-[#1443e0] mt-1 truncate">{{ $dueDate }}</div>
                    </div>
                </div>
            </div>

            <!-- Contract Expiration Card (smaller) -->
            <div
                class="col-span-1 md:col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col justify-center items-center">
                <span class="text-xs font-medium text-[#1443e0]">Contract Expiration</span>
                <div class="text-2xl font-bold text-[#1443e0] mt-2"></div>
            </div>
        </div>

        <!-- Advisory Modal -->
        <div x-show="showModal" style="display: none;" x-transition:enter="ease-out duration-300"
            x-transition:leave="ease-in duration-200" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                    <div class="absolute inset-0 bg-black opacity-50"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                    @click.away="showModal = false">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-xl font-bold text-[#1443e0]" x-text="activeAdvisory?.headline"></h3>
                                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4">
                                    <div x-show="activeAdvisory?.attachment"
                                        class="mb-4 bg-gray-100 rounded-lg overflow-hidden flex justify-center">
                                        <img :src="activeAdvisory?.attachment_url || ''"
                                            class="max-h-[300px] w-auto object-contain" :alt="activeAdvisory?.headline">
                                    </div>
                                    <div class="prose max-w-none" x-html="activeAdvisory?.description"></div>
                                    <br>
                                    <div class="prose max-w-none" x-html="activeAdvisory?.content"></div>
                                    <template x-if="activeAdvisory?.link">
                                        <div class="mt-6">
                                            <a :href="activeAdvisory.link" target="_blank" rel="noopener noreferrer"
                                                class="inline-block px-4 py-2 bg-[#1443e0] text-white rounded hover:bg-[#0d3ab9] transition">
                                                Visit Link
                                            </a>
                                        </div>
                                    </template>

                                    <div class="mt-4 text-sm text-gray-500">
                                        Published on
                                        <span
                                            x-text="activeAdvisory ? new Date(activeAdvisory.created_at).toLocaleString('en-US', {
                                            month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
                                        }) : ''"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="showModal = false"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#1443e0] text-base font-medium text-white hover:bg-[#0d3ab9] sm:ml-3 sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <iframe src="http://localhost:8000/app" class="w-full h-[600px] rounded-xl border-none" frameborder="0"
            allowFullScreen="true"></iframe>
    </div>
</x-layouts.app>

@vite('resources/js/app.js')
