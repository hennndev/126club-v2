<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'code' => 'ROOM',
                'name' => 'VIP Room',
                'capacity' => 10,
                'description' => 'Private VIP rooms with exclusive service',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'BLCY',
                'name' => 'Balcony',
                'capacity' => 6,
                'description' => 'Outdoor balcony area with city view',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'LNG',
                'name' => 'Main Lounge',
                'capacity' => 8,
                'description' => 'Main floor lounge area',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'BAR',
                'name' => 'Bar Counter',
                'capacity' => 2,
                'description' => 'Bar counter seating area',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'code' => 'DJ',
                'name' => 'DJ Booth Side',
                'capacity' => 6,
                'description' => 'Premium area near DJ booth',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
