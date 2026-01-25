<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\InternalUser;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $areas = Area::all();
        
        // Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@126club.com',
            'password' => Hash::make('password'),
        ]);
        $adminProfile = UserProfile::create([
            'user_id' => $admin->id,
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'birth_date' => '1990-01-15',
        ]);
        InternalUser::create([
            'user_id' => $admin->id,
            'user_profile_id' => $adminProfile->id,
            'area_id' => null,
            'is_active' => true,
        ]);
        $adminRole = Role::where('name', 'Administrator')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }
    }
}
