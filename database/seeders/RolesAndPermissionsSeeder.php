<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define modules
        $modules = [
            'po-order',
            'settings',
            'vendors',
            'shipping-to-address',
            'roles',
            'permissions',
            'teams',
        ];
        $actions = ['create', 'read', 'update', 'delete'];

        $permissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = "$module.$action";
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $agent = Role::firstOrCreate(['name' => 'Agent']);

        // Assign all permissions to Admin
        $admin->syncPermissions($permissions);

        // Assign only PO Order and Vendors CRUD permissions to Agent
        $agentPermissions = [];
        foreach (['po-order', 'vendors'] as $module) {
            foreach ($actions as $action) {
                $agentPermissions[] = "$module.$action";
            }
        }
        $agent->syncPermissions($agentPermissions);
    }
}