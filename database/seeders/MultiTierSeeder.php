<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentAuthorizedPhone;
use App\Models\Client;
use App\Models\MyfatoorahCredential;
use App\Models\User;
use App\Models\WhatsappKeyword;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultiTierSeeder extends Seeder
{
    /**
     * Seed multi-tier test data.
     */
    public function run(): void
    {
        // 1. Create Client: Fly Dubai
        $client = Client::create([
            'name' => 'Fly Dubai',
            'whatsapp_number' => '+96550000001',
            'iata_number' => 'FD12345',
            'company_email' => 'support@flydubai.test',
            'phone' => '+96550000001',
            'address' => 'Dubai, UAE - Kuwait Office',
            'timezone' => 'Asia/Kuwait',
            'service_fee_type' => 'fixed',
            'service_fee_value' => 0.250, // 0.250 KWD per transaction
            'service_fee_payer' => 'agent',
            'subscription_status' => 'active',
            'is_active' => true,
        ]);

        $this->command->info("Created Client: {$client->name} (ID: {$client->id})");

        // 2. Create MyFatoorah credentials for the client
        MyfatoorahCredential::create([
            'client_id' => $client->id,
            'api_key' => 'SK_KWT_wI6G2SOOeAogGyudgauY0dAEQAneSTSkSGMT8s49yyZzcKIK8MMw2bU9cj6VpiCo',
            'country_code' => 'KWT',
            'base_url' => 'https://apitest.myfatoorah.com',
            'is_test_mode' => true,
            'is_active' => true,
        ]);

        $this->command->info("Created MyFatoorah credentials for {$client->name}");

        // 3. Create Client Admin
        $clientAdmin = User::create([
            'client_id' => $client->id,
            'username' => '+96550000002',
            'full_name' => 'Ahmed Al-Khalid',
            'email' => 'admin@flydubai.test',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_CLIENT_ADMIN,
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'is_active' => true,
            'visible_to_clients' => true,
        ]);

        $this->command->info("Created Client Admin: {$clientAdmin->full_name}");

        // 4. Create Sales Person: Sara Ahmed
        $salesPerson = User::create([
            'client_id' => $client->id,
            'username' => '+96550000003',
            'full_name' => 'Sara Ahmed',
            'email' => 'sara@flydubai.test',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_SALES_PERSON,
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'is_active' => true,
            'visible_to_clients' => true,
        ]);

        $this->command->info("Created Sales Person: {$salesPerson->full_name}");

        // 5. Create Accountant: Finance Team
        $accountant = User::create([
            'client_id' => $client->id,
            'username' => '+96550000004',
            'full_name' => 'Finance Team',
            'email' => 'finance@flydubai.test',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ACCOUNTANT,
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
            'is_active' => true,
            'visible_to_clients' => true,
        ]);

        $this->command->info("Created Accountant: {$accountant->full_name}");

        // 6. Create Agent: City Travelers
        $agent = Agent::create([
            'client_id' => $client->id,
            'sales_person_id' => $salesPerson->id,
            'company_name' => 'City Travelers',
            'iata_number' => 'IATA12345',
            'email' => 'info@citytravelers.test',
            'email_verified_at' => now(),
            'accountant_whatsapp' => '+96587654321',
            'address' => 'Kuwait City, Sharq',
            'phone' => '+96599800027',
            'is_active' => true,
            'notes' => 'Premium travel agency partner',
        ]);

        $this->command->info("Created Agent: {$agent->company_name}");

        // 7. Create Authorized Phone for Agent
        $authorizedPhone = AgentAuthorizedPhone::create([
            'agent_id' => $agent->id,
            'phone_number' => '+96599800027',
            'full_name' => 'Mohammed Ali',
            'is_verified' => true,
            'verified_at' => now(),
            'is_active' => true,
        ]);

        $this->command->info("Created Authorized Phone: {$authorizedPhone->phone_number}");

        // Add second authorized phone
        AgentAuthorizedPhone::create([
            'agent_id' => $agent->id,
            'phone_number' => '+96599800028',
            'full_name' => 'Fatima Hassan',
            'is_verified' => true,
            'verified_at' => now(),
            'is_active' => true,
        ]);

        // 8. Create WhatsApp Keywords for Fly Dubai
        $keywords = [
            ['keyword' => 'top up', 'action' => 'payment_request'],
            ['keyword' => 'topup', 'action' => 'payment_request'],
            ['keyword' => 'send link', 'action' => 'payment_request'],
            ['keyword' => 'payment', 'action' => 'payment_request'],
            ['keyword' => 'شحن', 'action' => 'payment_request'], // Arabic: top up
            ['keyword' => 'رابط', 'action' => 'payment_request'], // Arabic: link
            ['keyword' => 'balance', 'action' => 'balance_check'],
            ['keyword' => 'رصيد', 'action' => 'balance_check'], // Arabic: balance
            ['keyword' => 'status', 'action' => 'status_check'],
            ['keyword' => 'help', 'action' => 'help'],
            ['keyword' => 'مساعدة', 'action' => 'help'], // Arabic: help
        ];

        foreach ($keywords as $kw) {
            WhatsappKeyword::create([
                'client_id' => $client->id,
                'keyword' => $kw['keyword'],
                'action' => $kw['action'],
                'is_active' => true,
            ]);
        }

        $this->command->info("Created " . count($keywords) . " WhatsApp keywords");

        // 9. Mark Platform Owner (soud@alphia.net - user ID 3)
        $platformOwner = User::where('email', 'soud@alphia.net')->first();
        if ($platformOwner) {
            $platformOwner->update([
                'role' => User::ROLE_PLATFORM_OWNER,
                'is_platform_owner' => true,
                'visible_to_clients' => false,
            ]);
            $this->command->info("Marked {$platformOwner->email} as Platform Owner");
        }

        // Also mark any existing super_admin users
        User::where('role', 'super_admin')
            ->orWhere('is_platform_owner', true)
            ->update([
                'role' => User::ROLE_PLATFORM_OWNER,
                'is_platform_owner' => true,
                'visible_to_clients' => false,
            ]);

        $this->command->info('');
        $this->command->info('=== Multi-Tier Seeding Complete ===');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('┌─────────────────┬──────────────────┬─────────────┐');
        $this->command->info('│ Role            │ Email/Phone      │ Password    │');
        $this->command->info('├─────────────────┼──────────────────┼─────────────┤');
        $this->command->info('│ Client Admin    │ +96550000002     │ password123 │');
        $this->command->info('│ Sales Person    │ +96550000003     │ password123 │');
        $this->command->info('│ Accountant      │ +96550000004     │ password123 │');
        $this->command->info('└─────────────────┴──────────────────┴─────────────┘');
        $this->command->info('');
        $this->command->info('Agent: City Travelers (IATA12345)');
        $this->command->info('Authorized Phone: +96599800027 (Mohammed Ali)');
        $this->command->info('');
    }
}
