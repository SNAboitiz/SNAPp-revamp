<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Customers</h2>
            <div class="flex gap-2">
                <flux:modal.trigger name="create-customer">
                    <flux:button variant="primary">
                        Create Customer
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table>
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Short Name</th>
                        <th>Customer Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr
                        class="cursor-pointer hover:bg-gray-100 transition flux-btn-info"
                        data-id="{{ $customer->id }}"
                        data-account-name="{{ $customer->account_name }}"
                        data-short-name="{{ $customer->short_name }}"
                        data-customer-number="{{ $customer->customer_number }}"
                        onclick="document.getElementById('open-edit-customer').click()">

                        <td>{{ $customer->account_name ?? '—' }}</td>
                        <td>{{ $customer->short_name ?? '—' }}</td>
                        <td>{{ $customer->customer_number ?? '—' }}</td>

                        <td class="text-center">
                            <div x-data @click.stop>
                                <flux:modal.trigger
                                    name="delete-customer"
                                    :data-customer-id="$customer->id"
                                    :data-customer-name="$customer->account_name">
                                    <flux:button
                                        icon="trash-2"
                                        variant="danger"
                                        class="flux-btn flux-btn-xs" />
                                </flux:modal.trigger>
                            </div>
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal trigger for edit -->
        <flux:modal.trigger name="edit-customer">
            <button id="open-edit-customer" class="hidden"></button>
        </flux:modal.trigger>
    </div>
    <!-- Modals -->
    @include('admin.customers.create')
    @include('admin.customers.edit')
    @include('admin.customers.delete')


</x-layouts.app>