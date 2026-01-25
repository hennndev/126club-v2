<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\CustomerUser;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Alexander Chen',
                'email' => 'alex.chen@email.com',
                'phone' => '084165432109',
                'address' => 'Jakarta Selatan',
                'birth_date' => '1988-05-15',
                'total_visits' => 35,
                'lifetime_spending' => 68000000, // 68jt - Untouchable
            ],
            [
                'name' => 'Vincent Hartono',
                'email' => 'vincent.h@email.com',
                'phone' => '087132109876',
                'address' => 'Jakarta Pusat',
                'birth_date' => '1990-08-20',
                'total_visits' => 31,
                'lifetime_spending' => 52000000, // 52jt - Untouchable
            ],
            [
                'name' => 'David Tanuhadi',
                'email' => 'david.t@email.com',
                'phone' => '081298765432',
                'address' => 'Tangerang',
                'birth_date' => '1985-03-10',
                'total_visits' => 28,
                'lifetime_spending' => 45000000, // 45jt - Recognized
            ],
            [
                'name' => 'Amanda Wijaya',
                'email' => 'amanda.w@email.com',
                'phone' => '081234567890',
                'address' => 'Jakarta Barat',
                'birth_date' => '1992-11-25',
                'total_visits' => 26,
                'lifetime_spending' => 42000000, // 42jt - Recognized
            ],
            [
                'name' => 'Sarah Angelina',
                'email' => 'sarah.a@email.com',
                'phone' => '085154321098',
                'address' => 'Bekasi',
                'birth_date' => '1995-07-18',
                'total_visits' => 22,
                'lifetime_spending' => 28000000, // 28jt - Recognized
            ],
            [
                'name' => 'Michael Tan',
                'email' => 'michael.tan@email.com',
                'phone' => '082345678901',
                'address' => 'Jakarta Timur',
                'birth_date' => '1987-02-28',
                'total_visits' => 19,
                'lifetime_spending' => 26000000, // 26jt - Recognized
            ],
            [
                'name' => 'Jessica Tanuwijaya',
                'email' => 'jessica.t@email.com',
                'phone' => '088121098765',
                'address' => 'Jakarta Utara',
                'birth_date' => '1993-09-14',
                'total_visits' => 20,
                'lifetime_spending' => 24000000, // 24jt - Recognized
            ],
            [
                'name' => 'Michelle Williams',
                'email' => 'michelle.w@email.com',
                'phone' => '089876543210',
                'address' => 'Jakarta Selatan',
                'birth_date' => '1991-06-05',
                'total_visits' => 18,
                'lifetime_spending' => 22000000, // 22jt - Recognized
            ],
            [
                'name' => 'Robert Kusuma',
                'email' => 'robert.k@email.com',
                'phone' => '081357924680',
                'address' => 'Depok',
                'birth_date' => '1989-12-30',
                'total_visits' => 15,
                'lifetime_spending' => 18000000, // 18jt - Regular
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer.lee@email.com',
                'phone' => '082468135790',
                'address' => 'Jakarta Pusat',
                'birth_date' => '1994-04-22',
                'total_visits' => 12,
                'lifetime_spending' => 12000000, // 12jt - Regular
            ],
            [
                'name' => 'Kevin Sutanto',
                'email' => 'kevin.s@email.com',
                'phone' => '085791357924',
                'address' => 'Jakarta Barat',
                'birth_date' => '1996-01-08',
                'total_visits' => 10,
                'lifetime_spending' => 8000000, // 8jt - Regular
            ],
            [
                'name' => 'Patricia Wong',
                'email' => 'patricia.w@email.com',
                'phone' => '089135792468',
                'address' => 'Tangerang Selatan',
                'birth_date' => '1997-10-12',
                'total_visits' => 8,
                'lifetime_spending' => 6000000, // 6jt - Regular
            ],
        ];

        foreach ($customers as $index => $customerData) {
            // Create user
            $user = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('password'),
            ]);

            // Create user profile
            $profile = UserProfile::create([
                'user_id' => $user->id,
                'phone' => $customerData['phone'],
                'address' => $customerData['address'],
                'birth_date' => $customerData['birth_date'],
            ]);

            // Create customer user
            CustomerUser::create([
                'customer_code' => 'CUST-' . ($index + 1),
                'user_id' => $user->id,
                'user_profile_id' => $profile->id,
                'total_visits' => $customerData['total_visits'],
                'lifetime_spending' => $customerData['lifetime_spending'],
            ]);
        }

        $this->command->info('Customers seeded successfully!');
    }
}
