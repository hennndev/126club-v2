<?php

namespace Database\Seeders;

use App\Models\InternalUser;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{

    public function run(): void
    {
      $areas = Area::all();
      
      // Super Admin
      $superAdmin = User::firstOrCreate(
        ['email' => "admin@126club.com"],
        [
          'name' => "System Administrator",
          'password' => Hash::make('password')
        ]
      );
      $userProfile = UserProfile::firstOrCreate([
        'user_id' => $superAdmin->id,
      ], [
        'phone' => '081234567890',
        'address' => 'Jakarta, Indonesia',
        'birth_date' => '1990-01-15',
      ]);
      InternalUser::firstOrCreate([
        'user_id' => $superAdmin->id,
      ], [
        'user_profile_id' => $userProfile->id,
        'area_id' => null,
        'is_active' => true,
      ]);
      $superAdmin->assignRole('Administrator');

      // Manager
      $manager = User::firstOrCreate(
        ['email' => "john@126club.com"],
        [
          'name' => "John Manager",
          'password' => Hash::make('password')
        ]
      );
      $managerProfile = UserProfile::firstOrCreate([
        'user_id' => $manager->id,
      ], [
        'phone' => '081234567891',
        'address' => 'Jakarta, Indonesia',
        'birth_date' => '1992-03-20',
      ]);
      InternalUser::firstOrCreate([
        'user_id' => $manager->id,
      ], [
        'user_profile_id' => $managerProfile->id,
        'area_id' => $areas->first()->id ?? null,
        'is_active' => true,
      ]);
      $manager->assignRole('Manager');

      // Cashier 1
      $cashier1 = User::firstOrCreate(
        ['email' => "sarah@126club.com"],
        [
          'name' => "Sarah Cashier",
          'password' => Hash::make('password')
        ]
      );
      $cashier1Profile = UserProfile::firstOrCreate([
        'user_id' => $cashier1->id,
      ], [
        'phone' => '081234567892',
        'address' => 'Jakarta, Indonesia',
        'birth_date' => '1995-06-10',
      ]);
      InternalUser::firstOrCreate([
        'user_id' => $cashier1->id,
      ], [
        'user_profile_id' => $cashier1Profile->id,
        'area_id' => $areas->skip(1)->first()->id ?? null,
        'is_active' => true,
      ]);
      $cashier1->assignRole('Cashier');

      // Waiter
      $waiter = User::firstOrCreate(
        ['email' => "mike@126club.com"],
        [
          'name' => "Mike Waiter",
          'password' => Hash::make('password')
        ]
      );
      $waiterProfile = UserProfile::firstOrCreate([
        'user_id' => $waiter->id,
      ], [
        'phone' => '081234567893',
        'address' => 'Jakarta, Indonesia',
        'birth_date' => '1997-09-25',
      ]);
      InternalUser::firstOrCreate([
        'user_id' => $waiter->id,
      ], [
        'user_profile_id' => $waiterProfile->id,
        'area_id' => $areas->skip(2)->first()->id ?? null,
        'is_active' => true,
      ]);
      $waiter->assignRole('Waiter/Server');

      // Cashier 2 (Inactive)
      $cashier2 = User::firstOrCreate(
        ['email' => "lisa@126club.com"],
        [
          'name' => "Lisa Chen",
          'password' => Hash::make('password')
        ]
      );
      $cashier2Profile = UserProfile::firstOrCreate([
        'user_id' => $cashier2->id,
      ], [
        'phone' => '081234567894',
        'address' => 'Jakarta, Indonesia',
        'birth_date' => '1994-12-05',
      ]);
      InternalUser::firstOrCreate([
        'user_id' => $cashier2->id,
      ], [
        'user_profile_id' => $cashier2Profile->id,
        'area_id' => $areas->skip(3)->first()->id ?? null,
        'is_active' => false,
      ]);
      $cashier2->assignRole('Cashier');
    }
}
