<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DisplayMessageRequest;
use App\Models\User;

class DisplayMessageSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('customerUser')->get();

        if ($customers->isEmpty()) {
            $this->command->warn('Customers not found. Please run UsersSeeder first.');
            return;
        }

        $sampleMessages = [
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Girls Night Out! Love this place! 🔥',
                'tip' => null,
                'status' => 'displayed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Cheers to 126 Club! Best nightclub in town 🍾',
                'tip' => 100000,
                'status' => 'displayed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Congratulations Michael & Sarah on your engagement! 💍',
                'tip' => 200000,
                'status' => 'displayed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Happy Birthday Jessica! 🎉🎂 Best celebration ever!',
                'tip' => 150000,
                'status' => 'displayed',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Celebrating our 5th anniversary at 126 Club! ❤️',
                'tip' => null,
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'First time here and absolutely loving it! Great vibes! 🎵',
                'tip' => 50000,
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Thank you 126 Club for an amazing night! See you again soon!',
                'tip' => null,
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Cheers to the weekend! 🥂 Best club in the city!',
                'tip' => 75000,
                'status' => 'pending',
            ],
            [
                'customer_id' => $customers->random()->id,
                'message' => 'Bachelor party vibes! Thanks for the memories 126! 🎊',
                'tip' => 300000,
                'status' => 'rejected',
            ],
        ];

        foreach ($sampleMessages as $messageData) {
            DisplayMessageRequest::create($messageData);
        }

        $this->command->info('Display messages seeded successfully!');
    }
}
