<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Christmas Week Celebration',
                'slug' => 'christmas-week-celebration',
                'description' => 'Rayakan Natal bersama kami dengan penampilan DJ special dan decorasi meriah! Dapatkan promo minuman khusus untuk perayaan Natal.',
                'start_date' => Carbon::create(2024, 12, 24),
                'end_date' => Carbon::create(2024, 12, 31),
                'start_time' => '20:00:00',
                'end_time' => '04:00:00',
                'is_active' => true,
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 3000000,
            ],
            [
                'name' => 'Black Friday VIP Night',
                'slug' => 'black-friday-vip-night',
                'description' => 'Special VIP night dengan diskon merchandise dan minuman! Live performance dari DJ ternama dan surprise guest stars.',
                'start_date' => Carbon::create(2024, 11, 29),
                'end_date' => Carbon::create(2024, 11, 29),
                'start_time' => '21:00:00',
                'end_time' => '05:00:00',
                'is_active' => true,
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 1500000,
            ],
            [
                'name' => 'Halloween Horror Night',
                'slug' => 'halloween-horror-night',
                'description' => 'Dress up dalam kostum terbaikmu dan menangkan hadiah utama! Live DJ, themed cocktails, dan horror decorations menanti!',
                'start_date' => Carbon::create(2024, 10, 31),
                'end_date' => Carbon::create(2024, 10, 31),
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'is_active' => true,
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 30,
            ],
            [
                'name' => 'New Year\'s Eve Countdown',
                'slug' => 'new-years-eve-countdown',
                'description' => 'Sambut tahun baru dengan kemeriahan! Live band, fireworks, champagne toast, dan special menu untuk malam tahun baru.',
                'start_date' => Carbon::create(2024, 12, 31),
                'end_date' => Carbon::create(2025, 1, 1),
                'start_time' => '21:00:00',
                'end_time' => '06:00:00',
                'is_active' => true,
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 5000000,
            ],
            [
                'name' => 'Valentine Special Romance Night',
                'slug' => 'valentine-special-romance-night',
                'description' => 'Malam romantis bersama pasangan dengan suasana intimate. Live jazz music, special couple packages, dan romantic decorations.',
                'start_date' => Carbon::create(2025, 2, 14),
                'end_date' => Carbon::create(2025, 2, 14),
                'start_time' => '19:00:00',
                'end_time' => '02:00:00',
                'is_active' => false,
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 25,
            ],
            [
                'name' => 'Independence Day Party',
                'slug' => 'independence-day-party',
                'description' => 'Rayakan kemerdekaan Indonesia dengan penuh semangat! Red & white theme, traditional modern fusion music, dan special Indonesian cocktails.',
                'start_date' => Carbon::create(2025, 8, 17),
                'end_date' => Carbon::create(2025, 8, 17),
                'start_time' => '20:00:00',
                'end_time' => '04:00:00',
                'is_active' => false,
                'price_adjustment_type' => 'fixed',
                'price_adjustment_value' => 2000000,
            ],
            [
                'name' => 'Summer Beach Party Week',
                'slug' => 'summer-beach-party-week',
                'description' => 'Beach vibes di tengah kota! Tropical cocktails, beach-themed decorations, dan DJ yang akan membawamu ke suasana pantai.',
                'start_date' => Carbon::create(2025, 6, 1),
                'end_date' => Carbon::create(2025, 6, 7),
                'start_time' => '21:00:00',
                'end_time' => '05:00:00',
                'is_active' => true,
                'price_adjustment_type' => 'percentage',
                'price_adjustment_value' => 20,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
