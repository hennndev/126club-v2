<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
          RolePermissionsSeeder::class,
          UsersSeeder::class,
          // AreaSeeder::class,
          // TabelSeeder::class,
          // CustomerSeeder::class,
          // TableReservationSeeder::class,
          // DisplayMessageSeeder::class,
          // SongRequestSeeder::class,
          // EventSeeder::class,
          // InventoryCategorySeeder::class,
          // InventoryItemSeeder::class,
          // BomSeeder::class,
          // KitchenOrderSeeder::class,
          // BarOrderSeeder::class,
        ]);
    }
}
