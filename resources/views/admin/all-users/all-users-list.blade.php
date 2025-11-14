<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Header with Add Buttons -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">All Users List</h2>
            <div class="flex gap-2">
                <!-- (Add New User button could go here) -->
            </div>
        </div>

        <form method="GET" action="{{ route('all-user-list') }}" class="mb-4 flex flex-wrap items-center gap-4">
            <flux:input icon="magnifying-glass" name="search" placeholder="Search users..."
                value="{{ request('search') }}" class="w-full md:w-1/4" />

            <flux:select name="role" placeholder="Filter by role"
                class="w-full md:w-1/4 min-w-[150px] max-w-[200px]">
                <flux:select.option value="">All Roles</flux:select.option>
                @foreach ($roles as $role)
                    <flux:select.option value="{{ $role->name }}"
                        :selected="request('role') === '{{ $role->name }}'">
                        <div class="flex items-center gap-2">
                            <flux:icon.user variant="mini" class="text-zinc-400" /> {{ ucfirst($role->name) }}
                        </div>
                    </flux:select.option>
                @endforeach
            </flux:select>

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
            <table class="min-w-full text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Customer ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allUsers as $user)
                        <tr class="cursor-pointer transition flux-btn-info
                        {{ $user->active ? 'hover:bg-gray-100' : 'bg-red-50 text-gray-400' }}"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}" data-customer-id="{{ $user->customer_id }}"
                            data-account-name="{{ $user->profile?->account_name }}"
                            data-role="{{ $user->roles->pluck('name')->join(', ') }}"
                            data-active="{{ $user->active ? '1' : '0' }}"
                            onclick="document.getElementById('open-edit-modal').click()">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td>{{ $user->profile?->account_name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Hidden Modal Trigger -->
        <flux:modal.trigger name="all-users-modal">
            <button id="open-edit-modal" class="hidden"></button>
        </flux:modal.trigger>

    </div>

    {{-- Include the edit modal partial --}}
    @include('admin.all-users.all-users-modal')
</x-layouts.app>
