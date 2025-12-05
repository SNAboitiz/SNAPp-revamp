<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Facilities</h2>
            <div class="flex gap-2">
                <flux:modal.trigger name="facility-modal">
                    <flux:button variant="primary">
                        Create Facility
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Short Name</th>
                        <th>Customer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facilities as $facility)
                    <tr
                        class="cursor-pointer hover:bg-gray-100 transition flux-btn-info"
                        data-id="{{ $facility->id }}"
                        data-name="{{ $facility->name }}"
                        data-sein="{{ $facility->sein }}"
                        data-customer-id="{{ $facility->customer_id }}"
                        onclick="document.getElementById('open-edit-facility').click()">

                        <td>{{ $facility->name ?? '—' }}</td>
                        <td>{{ $facility->sein ?? '—' }}</td>
                        <td>{{ $facility->customer->account_name ?? '—' }}</td>
                        <td class="text-center">
                            <div x-data @click.stop>
                                <flux:modal.trigger
                                    name="delete-facility"
                                    :data-facility-id="$facility->id"
                                    :data-facility-name="$facility->name">
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
        <flux:modal.trigger name="edit-facility">
            <button id="open-edit-facility" class="hidden"></button>
        </flux:modal.trigger>
    </div>

    <!-- Modals -->
    @include('admin.facilities.create')
    @include('admin.facilities.edit')
    @include('admin.facilities.delete')

</x-layouts.app>