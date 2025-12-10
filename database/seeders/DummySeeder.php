<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            $this->local();
        } else {
            $this->uat();
        }
    }

    /**
     * Seed local environment with dummy data.
     *
     * @return void
     */
    private function local()
    {
        $records = [
            [
                'customer' => [
                    'account_name' => 'LINDE BALAMBAN',
                    'short_name' => 'LINDE_BALAMBAN',
                ],
                'facility' => [
                    'name' => 'Linde Balamban Facility',
                    'sein' => 'LINDE-BALAMBAN',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'ISM',
                    'short_name' => 'ISM',
                ],
                'facility' => [
                    'name' => 'ISM Facility',
                    'sein' => 'ISM-FACILITY',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PLILI',
                    'short_name' => 'PLILI',
                ],
                'facility' => [
                    'name' => 'PLILI Facility',
                    'sein' => 'PLILI-FACILITY',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PFMCI',
                    'short_name' => 'PFMCI',
                ],
                'facility' => [
                    'name' => 'FMC Pasta',
                    'sein' => 'PFMC-Pasta',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PHILIPPINE FOREMOST MILLING CORPORATION',
                    'customer_number' => '2043',
                    'short_name' => 'PFMC',
                ],
                'facility' => [
                    'name' => 'FMC Manila',
                    'sein' => 'PFMC-Manila',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'CATHAY METAL CORPORATION ',
                    'customer_number' => '1102',
                    'short_name' => 'CTHAY1R',
                ],
                'facility' => [
                    'name' => 'Cathay Metal Facility',
                    'sein' => 'CATHAY-METAL',
                ],
            ],
        ];

        foreach ($records as $record) {
            Customer::factory()
                ->has(
                    Facility::factory()
                        ->state($record['facility'])
                )
                ->has(
                    User::factory()
                        ->count(3)
                        ->state(function (array $attributes, Customer $customer) {
                            return [
                                'customer_id' => $customer->id,
                                'facility_id' => $customer->facilities->first()->id,
                            ];
                        })
                )
                ->create($record['customer'])
                ->each(fn (Customer $customer) => $customer->users->each(fn (User $user) => $user->assignRole('customer')));
        }
    }

    /**
     * Seed UAT environment with dummy data.
     *
     * @return void
     */
    private function uat()
    {
        $records = [
            [
                'customer' => [
                    'account_name' => 'LINDE BALAMBAN',
                    'customer_number' => '3001',
                    'short_name' => 'LINDE_BALAMBAN',
                ],
                'facility' => [
                    'name' => 'Linde Balamban Facility',
                    'sein' => 'LINDE-BALAMBAN',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'ISM',
                    'customer_number' => '3002',
                    'short_name' => 'ISM',
                ],
                'facility' => [
                    'name' => 'ISM Facility',
                    'sein' => 'ISM-FACILITY',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PLILI',
                    'customer_number' => '3003',
                    'short_name' => 'PLILI',
                ],
                'facility' => [
                    'name' => 'PLILI Facility',
                    'sein' => 'PLILI-FACILITY',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PFMCI',
                    'customer_number' => '2044',
                    'short_name' => 'PFMCI',
                ],
                'facility' => [
                    'name' => 'FMC Pasta',
                    'sein' => 'PFMC-Pasta',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'PHILIPPINE FOREMOST MILLING CORPORATION',
                    'customer_number' => '2043',
                    'short_name' => 'PFMC',
                ],
                'facility' => [
                    'name' => 'FMC Manila',
                    'sein' => 'PFMC-Manila',
                ],
            ],
            [
                'customer' => [
                    'account_name' => 'CATHAY METAL CORPORATION ',
                    'customer_number' => '1102',
                    'short_name' => 'CTHAY1R',
                ],
                'facility' => [
                    'name' => 'Cathay Metal Facility',
                    'sein' => 'CATHAY-METAL',
                ],
            ],
        ];

        foreach ($records as $record) {
            $customer = Customer::create($record['customer']);

            $facility = Facility::create(array_merge(
                $record['facility'],
                ['customer_id' => $customer->id]
            ));

            Profile::create([
                'facility_id' => $facility->id,
                'customer_id' => $customer->id,
            ]);

            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'name' => 'Customer '.($i + 1).' of '.$customer->short_name,
                    'email' => 'customer'.($i + 1).'_'.strtolower($customer->short_name).'@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'customer_id' => $customer->id,
                    'facility_id' => $facility->id,
                ])
                    ->assignRole('customer');
            }
        }
    }
}
