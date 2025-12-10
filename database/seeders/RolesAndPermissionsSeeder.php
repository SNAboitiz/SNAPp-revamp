<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions list
        $permissions = [
            'can add permission',
            'can add role',
            'can view contracts',
            'can see customer information',
            'can upload contracts',
            'can view profile',
            'can view bills',
            'can view energy consumption page',
            'can view advisories',
            'profile-editable-AE',
            'profile-editable-customer',
            'can view econ',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $accountExecutive = Role::firstOrCreate(['name' => 'account executive']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Assign all permissions to admin
        $admin->syncPermissions(Permission::all());

        // Assign permissions to account executive
        $accountExecutive->syncPermissions([
            'can view contracts',
            'can see customer information',
            'can upload contracts',
            'can view profile',
            'profile-editable-AE',
        ]);

        // Assign permissions to customer
        $customer->syncPermissions([
            'can view contracts',
            'can see customer information',
            'can view profile',
            'can view bills',
            'can view econ',
            'profile-editable-customer',
            'can view advisories',
        ]);
    }
}
