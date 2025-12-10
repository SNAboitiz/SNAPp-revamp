<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
}
