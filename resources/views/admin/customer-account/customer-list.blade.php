<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Header with Add Button -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Customer Accounts</h2>
            <div class="flex gap-2">
                <flux:modal.trigger name="customer-modal">
                    <flux:button variant="primary">
                        Add Customer Account
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
        <form method="GET" action="{{ route('users.index') }}" class="mb-4 flex flex-wrap items-center gap-4">
            <flux:input icon="magnifying-glass" name="search" placeholder="Search users..."
                value="{{ request('search') }}" class="w-full md:w-1/4" />

            <flux:select name="active" placeholder="Status" class="w-full md:w-1/6 min-w-[150px] max-w-[180px]">
                <flux:select.option value="">All Status</flux:select.option>
                <flux:select.option value="1" :selected="request('active') === '1'">Active
                </flux:select.option>
                <flux:select.option value="0" :selected="request('active') === '0'">Inactive
                </flux:select.option>
            </flux:select>

            <flux:select name="sort" placeholder="Sort by" class="w-full md:w-1/6 min-w-[150px] max-w-[180px]">
                <flux:select.option value="">Default</flux:select.option>
                <flux:select.option value="name_asc" :selected="request('sort') === 'name_asc'">Name A–Z
                </flux:select.option>
                <flux:select.option value="name_desc" :selected="request('sort') === 'name_desc'">Name Z–A
                </flux:select.option>
                <flux:select.option value="created_at_desc" :selected="request('sort') === 'created_at_desc'">Newest
                </flux:select.option>
                <flux:select.option value="created_at_asc" :selected="request('sort') === 'created_at_asc'">Oldest
                </flux:select.option>
            </flux:select>

            <flux:button type="submit" variant="primary" class="self-end">
                Apply Filters
            </flux:button>
        </form>

        <!-- Clickable Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-center">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Customer</th>
                        <th>Facility</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="cursor-pointer hover:bg-gray-100 transition flux-btn-info
                                   {{ $user->active ? 'hover:bg-gray-100' : 'bg-red-50 text-gray-400' }}"
<<<<<<< HEAD
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-customer-id="{{ $user->customer_id }}"
                        data-facility-id="{{ $user->facility_id }}"
                        data-account-name="{{ $user->customer?->account_name }}"
                        data-facility-name="{{ $user->facility?->name }}"
                        onclick="document.getElementById('open-edit-modal').click()">

                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->customer?->account_name ?? '-' }}</td>
                        <td>{{ $user->facility?->name ?? '-' }}</td>

                    </tr>
=======
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}" data-customer-id="{{ $user->customer_id }}"
                            data-account-name="{{ $user->profile?->account_name }}"
                            onclick="document.getElementById('open-edit-modal').click()">

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->profile?->account_name ?? '-' }}</td>
                        </tr>
>>>>>>> f1a9b3a64940d1f4da23dedc8fa037b365cfee9b
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-customer-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    <!-- Modals -->
    @include('admin.customer-account.form-edit-customer')
    @include('admin.customer-account.form-create-customer')

</x-layouts.app>
