<x-layouts.app>
    <div class="h-screen w-full flex flex-col">
        <div class="px-4 py-6 flex flex-col flex-1">
            <!-- Select Filters Container -->
            <div class="flex gap-4 mb-6 flex-wrap">
                <flux:select name="date" placeholder="Select Date" class="w-40 min-w-[150px] max-w-[180px]">
                    <flux:select.option value="">All Dates</flux:select.option>
                    <flux:select.option value="last_7_days" :selected="request('date') === 'last_7_days'">Last 3 Months
                    </flux:select.option>
                    <flux:select.option value="last_30_days" :selected="request('date') === 'last_30_days'">Last 6 Months
                    </flux:select.option>
                    <flux:select.option value="this_month" :selected="request('date') === 'this_month'">This Month
                    </flux:select.option>
                    <flux:select.option value="last_month" :selected="request('date') === 'last_month'">Last Month
                    </flux:select.option>
                </flux:select>
            </div>

            <!-- Main Grid Content -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">

                <!-- Chart Section -->
                <div class="lg:col-span-8 flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#1443e0]">Energy Consumption</h2>
                    </div>

                    <!-- Looker Embed -->
                    <div class="mt-6 flex-1 bg-white rounded-2xl shadow overflow-hidden">
                        <iframe src="{{ config('app.url') }}/app" class="w-full h-full rounded-xl border-none"
                            frameborder="0" allowFullScreen="true"></iframe>
                    </div>

                </div>

                <!-- Metrics Section -->
                <div class="lg:col-span-4 flex flex-col bg-white rounded-2xl shadow p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-medium text-[color:var(--color-accent)] text-sm md:text-base">
                            Environmental Impact</h2>
                    </div>

                    <div class="mt-2 text-3xl font-bold text-[color:var(--color-accent)]">
                        <span class="text-black">{{ $consumption }}</span>
                        <span class="text-[color:var(--color-accent)]">kWh</span>
                    </div>

                    <hr class="my-4">

                    <div class="flex flex-col gap-4 flex-grow justify-between">
                        <div class="flex flex-col items-center gap-1 flex-1">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 md:w-8 md:h-8 bulb-icon-masked"></div>
                                <span
                                    class="text-2xl md:text-3xl text-black font-semibold">{{ $bulbReplacement }}</span>
                            </div>
                            <small class="text-gray-700 text-center">Incadescent bulbs switched to LED bulbs</small>
                        </div>

                        <div class="flex flex-col items-center gap-1 flex-1">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('images/co2-icon.png') }}" alt="CO₂ Icon"
                                    class="w-6 h-6 md:w-8 md:h-8">
                                <span
                                    class="text-2xl md:text-3xl text-black font-semibold">{{ $avoidedEmissions }}</span>
                            </div>
                            <small class="text-gray-700 text-center">Avoided GHG Emissions</small>
                        </div>

                        <div class="flex flex-col items-center gap-1 flex-1">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('images/tree-icon.png') }}" alt="Tree Icon"
                                    class="w-6 h-6 md:w-8 md:h-8">
                                <span class="text-2xl md:text-3xl text-black font-semibold">{{ $treesGrown }}</span>
                            </div>
                            <small class="text-gray-700 text-center">Tree seedlings grown for 10 years</small>
                        </div>
                    </div>
                </div>

                <!-- Bottom Stat Cards -->
                <!-- <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-primary p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 1</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">123</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-info p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 2</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">456</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-danger p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 3</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">789</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-6 lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow border-b-4 border-warning p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-[color:var(--color-accent)]">Data 4</span>
                            <span class="text-2xl md:text-3xl font-semibold text-black">101</span>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</x-layouts.app>

<style>
    /* Core scrollbar prevention */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        /* overflow: hidden; */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .lg\:col-span-8,
        .lg\:col-span-4,
        .col-span-6 {
            grid-column: span 12;
        }

        /* Allow vertical scrolling on mobile if needed */

    }

    .bulb-icon-masked {
        background-color: #eebd35;
        mask: url('/images/bulb-icon.png') no-repeat center;
        mask-size: contain;
        -webkit-mask: url('/images/bulb-icon.png') no-repeat center;
        -webkit-mask-size: contain;
    }
</style>
