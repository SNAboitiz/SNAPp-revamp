<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Top Bar: Search + Export + Tabs -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

<form method="GET" action="" class="w-full sm:w-auto flex items-center gap-4">

    <flux:input icon="magnifying-glass" name="search" placeholder="Search..." value="{{ request('search') }}"
        class="w-full md:w-64" />

    @if ($activeTab === 'payments')
        @role('admin')
            <flux:dropdown position="right-start">
                <button type="button"
                    class="inline-flex justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                    Customer
                    <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <flux:menu class="w-48">
                    @foreach ($customers as $customer)
                        <flux:menu.item
                            href="{{ route('bills.show', ['customer_id' => $customer->customer_id]) }}"
                            icon="user">
                            {{ $customer->account_name }}
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>
        @endrole
    @endif

    @if ($activeTab === 'bills')
        <flux:dropdown position="right-start">
            <button type="button" 
                class="inline-flex justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                Customer
                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
            <flux:menu class="w-48">
                @foreach ($customers as $customer)
                    <flux:menu.item
                        href="{{ route('bills.show', ['customer_id' => $customer->customer_id]) }}"
                        icon="user">
                        {{ $customer->account_name }}
                    </flux:menu.item>
                @endforeach

                <flux:menu.item href="#" icon="x-circle">
                    {{ __('Clear Filters') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:dropdown position="right-start">
            <button type="button"
                class="inline-flex justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                Facility
                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <flux:menu class="w-48">
                @foreach ($facilities as $facility)
                    <flux:menu.item
                        href="{{ route('bills.show', array_merge(request()->query(), ['facility' => $facility])) }}">
                        {{ $facility }}
                    </flux:menu.item>
                @endforeach

                <flux:menu.item href="{{ route('bills.show', request()->except('facility')) }}"
                    icon="x-circle">
                    {{ __('Clear Facility Filter') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    @endif
</form>

            <!-- Button Group -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-2">
                <flux:button.group class="w-full">
                    <flux:button href="{{ route('bills.show') }}"
                        variant="{{ $activeTab === 'bills' ? 'primary' : 'outline' }}" class="w-full sm:w-auto">
                        Bills Summary
                    </flux:button>

                    <flux:button href="{{ route('payments.history') }}"
                        variant="{{ $activeTab === 'payments' ? 'primary' : 'outline' }}" class="w-full sm:w-auto">
                        Payment History
                    </flux:button>
                </flux:button.group>
            </div>

        </div>

        <!-- Tables -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            @if ($activeTab === 'bills')
            @include('bills.bill-summary-table', ['data' => $bills])
            @endif

            @if ($activeTab === 'payments')
            @include('bills.payments-history-table', ['data' => $payments])
            @endif
        </div>
    </div>
    <!-- Custom Export Loader -->
    <div id="export-loader" class="hidden fixed inset-0 bg-white/75 z-[9999] flex items-center justify-center">
        <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded inline-flex items-center" disabled>
            <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            Exporting…
        </button>
    </div>

    <!-- JavaScript -->
    <script>
        function downloadThenRedirect(fileUrl, returnUrl) {
            const loader = document.getElementById('export-loader');
            loader.classList.remove('hidden');

            fetch(fileUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error during export');

                    const disposition = response.headers.get('Content-Disposition');
                    let filename = 'export.csv';

                    if (disposition && disposition.includes('filename=')) {
                        filename = disposition.split('filename=')[1].replace(/"/g, '');
                    }

                    return response.blob().then(blob => ({
                        blob,
                        filename
                    }));
                })
                .then(({
                    blob,
                    filename
                }) => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                })
                .catch(err => {
                    alert('Failed to export file.');
                    console.error(err);
                })
                .finally(() => {
                    loader.classList.add('hidden');
                    window.location.href = returnUrl;
                });
        }
    </script>

</x-layouts.app>