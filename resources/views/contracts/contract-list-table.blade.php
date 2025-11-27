<div class="min-w-full divide-y divide-gray-200">
    <table>
        <thead>
            <tr>
                <!-- <th>Reference No.</th> -->
                <th>Description</th>
                <th>Short Name</th>
                <th>Contract Period</th>
                <th>Upload Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $item)
                <tr @if ($item['gcsPdfUrl']) class="cursor-pointer hover:bg-gray-100 transition"
                @click="openContractViewer($el)" @endif
                    data-contract-name="{{ $item['contract_name'] }}" data-gcs-pdf-url="{{ $item['gcsPdfUrl'] ?? '' }}">

                    <!-- <td>{{ $item['reference_number'] }}</td> -->
                    <td class="text-black">{{ $item['contract_name'] }}</td>
                    <td class="text-black">{{ $item['short_name'] }}</td>
                    <td class="text-black">{{ $item['contract_period'] }}</td>
                    <td class="text-black">{{ $item['upload_date'] }}</td>
                    <td>
                        <span
                            class="px-2 py-1 rounded-full text-xs
        {{ $item['status'] === 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ \Str::upper($item['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
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
                    <td colspan="6" class="text-center py-4 text-gray-500">No contract found for your account.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('contracts.view-contract-modal')
