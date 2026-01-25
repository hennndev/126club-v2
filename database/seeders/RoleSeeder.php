<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Area Management
            'view-areas',
            'create-areas',
            'edit-areas',
            'delete-areas',
            
            // Table Management
            'view-tables',
            'create-tables',
            'edit-tables',
            'delete-tables',
            
            // Reservation
            'view-reservations',
            'create-reservations',
            'edit-reservations',
            'delete-reservations',
            
            // POS
            'view-pos',
            'create-orders',
            'edit-orders',
            'delete-orders',
            
            // Customer
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            
            // Reports
            'view-reports',
            'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Administrator - Full access
        $admin = Role::firstOrCreate(['name' => 'Administrator']);
        $admin->syncPermissions(Permission::all());

        // Manager - Most permissions except delete
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'view-dashboard',
            'view-areas',
            'view-tables',
            'view-reservations',
            'create-reservations',
            'edit-reservations',
            'view-pos',
            'view-customers',
            'create-customers',
            'edit-customers',
            'view-reports',
            'export-reports',
        ]);

        // Cashier - POS and booking focused
        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $cashier->syncPermissions([
            'view-dashboard',
            'view-tables',
            'view-reservations',
            'create-reservations',
            'view-pos',
            'create-orders',
            'view-customers',
        ]);

        // Waiter/Server - Limited POS access
        $waiter = Role::firstOrCreate(['name' => 'Waiter/Server']);
        $waiter->syncPermissions([
            'view-dashboard',
            'view-tables',
            'view-reservations',
        ]);
    }
}

