<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TableReservation;
use App\Models\User;
use App\Models\Tabel;
use Carbon\Carbon;

class TableReservationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('customerUser')->get();
        $tables = Tabel::all();

        if ($customers->isEmpty() || $tables->isEmpty()) {
            $this->command->warn('Customers or tables not found. Please run UsersSeeder and TabelSeeder first.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'];
        
        $bookings = [
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(2),
                'reservation_time' => '20:00:00',
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(3),
                'reservation_time' => '21:00:00',
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(1),
                'reservation_time' => '19:30:00',
                'status' => 'confirmed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(1),
                'reservation_time' => '22:00:00',
                'status' => 'confirmed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today(),
                'reservation_time' => '20:30:00',
                'status' => 'checked_in',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today(),
                'reservation_time' => '21:30:00',
                'status' => 'checked_in',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::yesterday(),
                'reservation_time' => '20:00:00',
                'status' => 'completed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::yesterday(),
                'reservation_time' => '22:00:00',
                'status' => 'completed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(5),
                'reservation_time' => '19:00:00',
                'status' => 'cancelled',
            ],
            [
                'customer_id' => $customers->random()->id,
                'table_id' => $tables->random()->id,
                'reservation_date' => Carbon::today()->addDays(7),
                'reservation_time' => '23:00:00',
                'status' => 'pending',
            ],
        ];

        foreach ($bookings as $index => $bookingData) {
            TableReservation::create(array_merge($bookingData, [
                'booking_code' => $index + 1
            ]));
        }

        $this->command->info('Table reservations seeded successfully!');
    }
}
