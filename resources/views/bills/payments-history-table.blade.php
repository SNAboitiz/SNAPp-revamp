<div class="min-w-full divide-y divide-gray-200">
    <table>
        <thead>
            <tr>
                <th>Payment Ref</th>
                <th>Payment Date</th>
                <th>Billing Period</th>
                <th>Amount</th>
                <th>Date Posted</th>
                <th>2307 Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['Payment Reference'] }}</td>
                    <td>{{ $item['Payment Reference Date'] }}</td>
                    <td>{{ $item['Billing Period'] }}</td>
                    <td>₱ {{ $item['Amount'] }}</td>
                    <td>{{ $item['Date Posted'] }}</td>
                    <td class="px-6 py-4 text-sm whitespace-nowrap text-center">
                        @if (isset($item['2307_uploaded']) && $item['2307_uploaded'] === true)
                            <a href="{{ Storage::temporaryUrl($item['2307_file_path'], now()->addMinutes(30)) }}"
                                target="_blank" class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                VIEW 2307
                            </a>
                        @else
                            <flux:modal.trigger name="upload-2307">
                                <button class="text-indigo-600 hover:text-indigo-900 font-semibold"
                                    data-billing-period="{{ $item['Billing Period'] }}"
                                    data-document-number="{{ $item['Payment Reference'] }}"
                                    data-customer-id="{{ !empty($item['customer_id']) ? $item['customer_id'] : auth()->user()->customer_id }}"
                                    data-facility-id="{{ !empty($item['facility_id']) ? $item['facility_id'] : auth()->user()->facility_id }}"
                                    onclick="prepare2307Upload(this)">
                                    Upload 2307
                                </button>
                            </flux:modal.trigger>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('bills.upload-2307')
@if ($data->hasPages())
    <div class="px-4 py-3 bg-white border-t border-gray-200">
        {{ $data->links() }}
    </div>
@endif
