<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerFacilitySeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'account_name' => 'PHILIPPINE FOREMOST MILLING CORPORATION',
                'customer_number' => '2043',
                'short_name' => 'PHFMC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_name' => 'CATHAY METAL CORPORATION ',
                'customer_number' => '1102',
                'short_name' => 'CTHAY1R',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert customers and get their IDs
        foreach ($customers as &$customer) {
            $customer['id'] = DB::table('customers')->insertGetId($customer);
        }

        $facilities = [
            [
                'name' => 'FMC Pasta',
                'sein' => 'PFMC-Pasta',
                'customer_id' => $customers[0]['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FMC Manila',
                'sein' => 'PFMC-Manila',
                'customer_id' => $customers[0]['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('facilities')->insert($facilities);
    }
}
