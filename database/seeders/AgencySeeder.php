<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;

class AgencySeeder extends Seeder
{
    /**
     * Seed test agencies.
     */
    public function run(): void
    {
        // Demo Travel Agency
        Agency::create([
            'agency_name' => 'City Tours Travel',
            'iata_number' => 'IATA12345',
            'address' => 'Kuwait City, Al-Salmiya, Block 5',
            'company_email' => '[email protected]',
            'phone' => '+96512345678',
            'timezone' => 'Asia/Kuwait',
            'is_active' => true,
        ]);

        // Second Demo Agency
        Agency::create([
            'agency_name' => 'Gulf Travel Agency',
            'iata_number' => 'IATA67890',
            'address' => 'Kuwait City, Hawalli, Block 2',
            'company_email' => '[email protected]',
            'phone' => '+96598765432',
            'timezone' => 'Asia/Kuwait',
            'is_active' => true,
        ]);
    }
}
