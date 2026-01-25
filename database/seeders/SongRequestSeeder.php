<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SongRequest;
use App\Models\CustomerUser;

class SongRequestSeeder extends Seeder
{
    public function run(): void
    {
        $customerUsers = CustomerUser::with('user')->get();

        if ($customerUsers->isEmpty()) {
            $this->command->warn('Customer users not found. Please run UsersSeeder and CustomerSeeder first.');
            return;
        }

        $sampleSongs = [
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Shape of You',
                'artist' => 'Ed Sheeran',
                'tip' => null,
                'status' => 'pending',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => "Don't Start Now",
                'artist' => 'Dua Lipa',
                'tip' => null,
                'status' => 'pending',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Starboy',
                'artist' => 'The Weeknd ft. Daft Punk',
                'tip' => 75000,
                'status' => 'pending',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Levitating',
                'artist' => 'Dua Lipa',
                'tip' => null,
                'status' => 'pending',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Blinding Lights',
                'artist' => 'The Weeknd',
                'tip' => 100000,
                'status' => 'pending',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Save Your Tears',
                'artist' => 'The Weeknd',
                'tip' => null,
                'status' => 'played',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Physical',
                'artist' => 'Dua Lipa',
                'tip' => 50000,
                'status' => 'played',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'As It Was',
                'artist' => 'Harry Styles',
                'tip' => null,
                'status' => 'played',
            ],
            [
                'customer_user_id' => $customerUsers->random()->id,
                'song_title' => 'Anti-Hero',
                'artist' => 'Taylor Swift',
                'tip' => 150000,
                'status' => 'rejected',
            ],
        ];

        foreach ($sampleSongs as $songData) {
            SongRequest::create($songData);
        }

        $this->command->info('Song requests seeded successfully!');
    }
}
