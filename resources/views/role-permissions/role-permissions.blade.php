<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Roles & Permissions</h2>

            <div class="flex gap-2">
                <!-- Role Modal Trigger -->
                @can('can add role')
                    <flux:modal.trigger name="create-role">
                        <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                            Add Role
                        </flux:button>
                    </flux:modal.trigger>
                @endcan
                <flux:modal.trigger name="create-permission">
                    <flux:button class="flux-btn flux-btn-primary flux-btn-sm">
                        Add Permission
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <div class="overflow-x-auto">
            <form action="{{ route('role.permission.store') }}" method="POST">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th>Permission / Role</th>
                            @foreach ($roles as $role)
                                <th>
                                    <div class="flex flex-col items-center">
                                        <span>{{ $role->name }}</span>
                                        <div class="flex gap-1 mt-1">
                                            <flux:modal.trigger name="delete-role">
                                                <flux:button icon="trash-2" variant="danger"
                                                    class="flux-btn flux-btn-xs flux-btn-danger">
                                                </flux:button>
                                            </flux:modal.trigger>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-800">
                        @foreach ($permissions as $permission)
                            <tr>
                                <td class="p-3 border border-gray-300">
                                    <div class="flex justify-between items-center">
                                        <span>{{ $permission->name }}</span>
                                        <div class="flex gap-1">
                                            <flux:modal.trigger name="delete-permission">
                                                <flux:button icon="trash-2" variant="danger"
                                                    class="flux-btn flux-btn-xs flux-btn-danger">
                                                </flux:button>
                                            </flux:modal.trigger>
                                        </div>
                                    </div>
                                </td>
                                @foreach ($roles as $role)
                                    <td class="text-center p-3 border border-gray-300">
                                        <input type="checkbox" name="permission[{{ $role->id }}][]"
                                            value="{{ $permission->id }}"
                                            @if ($role->hasPermissionTo($permission)) checked @endif class="form-check-input">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="p-4 border-t border-gray-300 text-center">
                                <flux:button type="submit" variant="primary"
                                    class="flux-btn flux-btn-success flux-btn-sm">
                                    Save Changes
                                </flux:button>
                            </td>
                            @foreach ($roles as $role)
                                <td class="border-t border-gray-300"></td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>

    @include('role-permissions.form-create-role')
    @include('role-permissions.form-create-permission')
    @include('role-permissions.delete-role')
    @include('role-permissions.delete-permission')
</x-layouts.app>
