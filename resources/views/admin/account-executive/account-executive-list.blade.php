<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Account Executive Accounts</h2>
            <div class="flex gap-2">
                <!-- Add Button -->
                <flux:modal.trigger name="create-accountexecutive">
                    <flux:button variant="primary">
                        Add Account Executive
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
        <form method="GET" action="{{ route('all-user-list') }}" class="mb-4 flex flex-wrap items-center gap-4">
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

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accountExecutives as $accountExecutive)
                        <tr class="cursor-pointer hover:bg-gray-100 transition flux-btn-info"
                            data-id="{{ $accountExecutive->id }}" data-name="{{ $accountExecutive->name }}"
                            data-email="{{ $accountExecutive->email }}"
                            data-customer-id="{{ $accountExecutive->customer_id }}"
                            data-account-name="{{ $accountExecutive->profile?->account_name }}"
                            onclick="document.getElementById('open-edit-modal').click()">
                            <td>{{ $accountExecutive->id }}</td>
                            <td>{{ $accountExecutive->name }}</td>
                            <td>{{ $accountExecutive->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-accountexecutive-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    @include('admin.account-executive.form-create-accountexecutive')
    @include('admin.account-executive.form-edit-accountexecutive')

</x-layouts.app>
