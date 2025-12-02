<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Admin Accounts</h2>
            <div class="flex gap-2">

                <!-- Flux Button to Trigger the Modal -->
                <flux:modal.trigger name="create-admin">
                    <flux:button variant="primary">
                        Add Admin Account
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
                    @foreach ($admins as $admin)
                        <tr class="cursor-pointer hover:bg-gray-100 transition flux-btn-info"
                            data-id="{{ $admin->id }}" data-name="{{ $admin->name }}"
                            data-email="{{ $admin->email }}" data-customer-id="{{ $admin->customer_id }}"
                            data-account-name="{{ $admin->customer?->account_name }}"
                            onclick="document.getElementById('open-edit-modal').click()">

                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger for Edit -->
        <flux:modal.trigger name="edit-admin-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>
    @include('admin.admin-account.form-edit-admin')
    @include('admin.admin-account.form-create-admin')


</x-layouts.app>
