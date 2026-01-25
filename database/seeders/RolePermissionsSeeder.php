<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionsSeeder extends Seeder
{
  public function run(): void
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create Permissions
    $permissions = [
      // user management
      'users-management',
      'roles-management'
    ];

    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $permission]);
    }

    // Create Roles
    $roles = [
      'Administrator',
      'Manager',
      'Cashier',
      'Waiter/Server'
    ];

    foreach ($roles as $roleName) {
      Role::firstOrCreate(['name' => $roleName]);
    }

    $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
    $adminRole->givePermissionTo(Permission::all());
  }
}
