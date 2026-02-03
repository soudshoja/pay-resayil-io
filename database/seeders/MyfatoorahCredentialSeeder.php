<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyfatoorahCredential;

class MyfatoorahCredentialSeeder extends Seeder
{
    /**
     * Seed MyFatoorah credentials for test agencies.
     */
    public function run(): void
    {
        // City Tours - Test credentials
        MyfatoorahCredential::create([
            'agency_id' => 1,
            'api_key' => 'SK_KWT_wI6G2SOOeAogGyudgauY0dAEQAneSTSkSGMT8s49yyZzcKIK8MMw2bU9cj6VpiCo',
            'country_code' => 'KWT',
            'is_test_mode' => true,
            'is_active' => true,
            'last_verified_at' => now(),
        ]);

        // Gulf Travel - Test credentials
        MyfatoorahCredential::create([
            'agency_id' => 2,
            'api_key' => 'SK_KWT_wI6G2SOOeAogGyudgauY0dAEQAneSTSkSGMT8s49yyZzcKIK8MMw2bU9cj6VpiCo',
            'country_code' => 'KWT',
            'is_test_mode' => true,
            'is_active' => true,
            'last_verified_at' => now(),
        ]);
    }
}
