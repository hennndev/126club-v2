<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kitchen',
                'code' => 'KITCHEN',
                'color' => 'orange',
                'is_active' => true,
            ],
            [
                'name' => 'Bar',
                'code' => 'BAR',
                'color' => 'purple',
                'is_active' => true,
            ],
            [
                'name' => 'BOM',
                'code' => 'BOM',
                'color' => 'blue',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            InventoryCategory::create($category);
        }
    }
}
