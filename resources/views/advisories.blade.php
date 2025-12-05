<x-layouts.app>
    <div class="h-full w-full px-4 py-6" x-data="{
        activeAdvisory: null,
        showModal: false,
        moreAdvisories: {{ Js::from($moreAdvisories) }},
        maxVisible: 3,
        showAll: false,
        scrollToBottom() {
            this.$nextTick(() => {
                this.$refs.advisoryList.scrollTo({
                    top: this.$refs.advisoryList.scrollHeight,
                    behavior: 'smooth'
                });
            });
        }
    }">

        <form method="GET" class="flex flex-col sm:flex-row gap-4 mb-4 md:hidden">
            <flux:select name="date" placeholder="Filter by" class="w-full sm:flex-1" @change="$el.form.submit()"
                default="latest">
                <flux:select.option default value="latest" :selected="request('date') === 'latest'">Latest
                </flux:select.option>
                <flux:select.option value="last_7_days" :selected="request('date') === 'last_7_days'">Last 7 Days
                </flux:select.option>
                <flux:select.option value="last_30_days" :selected="request('date') === 'last_30_days'">Last 30 Days
                </flux:select.option>
                <flux:select.option value="this_month" :selected="request('date') === 'this_month'">This Month
                </flux:select.option>
                <flux:select.option value="last_month" :selected="request('date') === 'last_month'">Last Month
                </flux:select.option>
            </flux:select>

            <flux:select name="sort" placeholder="Sort by" class="w-full sm:flex-1" @change="$el.form.submit()"
                default="date_desc">
                <flux:select.option default value="date_desc" :selected="request('sort') === 'date_desc'">Date
                    Descending
                </flux:select.option>
                <flux:select.option value="date_asc" :selected="request('sort') === 'date_asc'">Date Ascending
                </flux:select.option>
                <flux:select.option value="headline_asc" :selected="request('sort') === 'headline_asc'">Headline A-Z
                </flux:select.option>
                <flux:select.option value="headline_desc" :selected="request('sort') === 'headline_desc'">Headline Z-A
                </flux:select.option>
            </flux:select>
        </form>

        <div class="flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-3/5 flex flex-col min-h-[480px]">
                <div class="bg-white rounded-2xl shadow p-4 flex-1 flex flex-col">
                    <h2 class="text-lg font-bold text-[#1443e0]">Latest Advisory</h2>
                    <div
                        class="mt-2 bg-gray-100 w-full h-80 flex items-center justify-center rounded-lg overflow-hidden">
                        @if ($latestAdvisory?->attachment)
                            <img src="{{ $latestAdvisory->attachment_url }}" class="h-full w-full object-cover"
                                alt="{{ $latestAdvisory->headline }}">
                        @else
                            <div class="text-gray-400 p-4">No Image Available</div>
                        @endif
                    </div>
                    <div class="flex items-center mt-4 space-x-4 flex-wrap">
                        <div class="flex-shrink max-w-[60%]">
                            <h3 class="text-lg font-bold whitespace-normal">
                                {{ $latestAdvisory?->headline ?? 'No advisory available' }}
                            </h3>
                            <p class="text-sm mt-1 line-clamp-3">
                                {{ $latestAdvisory?->description }}
                            </p>
                        </div>
                        <button @click="activeAdvisory = {{ Js::from($latestAdvisory) }}; showModal = true"
                            class="bg-[#1443e0] text-white rounded-md px-3 py-1.5 text-sm font-medium hover:bg-[#0d3ab9] transition flex items-center whitespace-nowrap">
                            <span>Read More</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/5 flex flex-col min-h-[480px]">

                <form method="GET" class="hidden md:flex flex-col sm:flex-row gap-4 mb-4">
                    <flux:select name="date" placeholder="Filter by" class="w-full sm:flex-1" default="latest"
                        @change="$el.form.submit()">
                        <flux:select.option default value="latest" :selected="request('date') === 'latest'">Latest
                        </flux:select.option>
                        <flux:select.option value="last_7_days" :selected="request('date') === 'last_7_days'">Last 7
                            Days</flux:select.option>
                        <flux:select.option value="last_30_days" :selected="request('date') === 'last_30_days'">Last 30
                            Days</flux:select.option>
                        <flux:select.option value="this_month" :selected="request('date') === 'this_month'">This Month
                        </flux:select.option>
                        <flux:select.option value="last_month" :selected="request('date') === 'last_month'">Last Month
                        </flux:select.option>
                    </flux:select>

                    <flux:select name="sort" placeholder="Sort by" class="w-full sm:flex-1" default="date_desc"
                        @change="$el.form.submit()">
                        <flux:select.option default value="date_desc" :selected="request('sort') === 'date_desc'">Date
                            Descending</flux:select.option>
                        <flux:select.option value="date_asc" :selected="request('sort') === 'date_asc'">Date Ascending
                        </flux:select.option>
                        <flux:select.option value="headline_asc" :selected="request('sort') === 'headline_asc'">
                            Headline A-Z</flux:select.option>
                        <flux:select.option value="headline_desc" :selected="request('sort') === 'headline_desc'">
                            Headline Z-A</flux:select.option>
                    </flux:select>
                </form>

                <div class="bg-white rounded-2xl shadow p-4 flex flex-col flex-1">
                    <h2 class="text-md font-bold text-[#1443e0] mb-4">Recent Advisories</h2>
                    <div class="space-y-2 flex-grow overflow-y-auto pr-1" id="advisories-list" x-ref="advisoryList"
                        style="max-height: calc(100% - 80px);">
                        <template
                            x-for="(advisory, index) in showAll ? moreAdvisories : moreAdvisories.slice(0, maxVisible)"
                            :key="advisory.id">
                            <div @click="activeAdvisory = advisory; showModal = true"
                                class="cursor-pointer group hover:bg-blue-50 p-2 rounded-md transition border border-gray-100 hover:border-blue-100">
                                <h4 class="text-sm font-semibold group-hover:text-[#0d3ab9]" x-text="advisory.headline">
                                </h4>
                                <p class="text-xs mt-1 line-clamp-2" x-text="advisory.description"></p>
                                <div class="text-xs text-gray-500 mt-1"
                                    x-text="new Date(advisory.created_at).toLocaleDateString()"></div>
                            </div>
                        </template>
                        <template x-if="moreAdvisories.length === 0">
                            <p class="text-sm text-gray-500 p-2">No recent advisories available.</p>
                        </template>
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-100 text-center">
                        <template x-if="!showAll && moreAdvisories.length > maxVisible">
                            <button @click="showAll = true; scrollToBottom()"
                                class="text-[#1443e0] hover:text-[#0d3ab9] text-sm font-medium flex items-center justify-center w-full py-1">
                                <span>View All</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

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
                                    <h3 class="text-xl font-bold" x-text="activeAdvisory?.headline">
                                    </h3>
                                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-4">
                                    <div x-show="activeAdvisory?.attachment"
                                        class="mb-4 bg-gray-100 rounded-lg overflow-hidden flex justify-center">
                                        <img :src="activeAdvisory?.attachment_url || ''"
                                            class="max-h-[300px] w-auto object-contain"
                                            :alt="activeAdvisory?.headline">
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

    </div>
</x-layouts.app>
<script>
    document.querySelectorAll('form[method="GET"] select').forEach(select => {
        select.addEventListener('change', () => {
            select.form.submit();
        });
    });
</script>
