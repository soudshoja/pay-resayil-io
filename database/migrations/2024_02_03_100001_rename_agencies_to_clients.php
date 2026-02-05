<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename agencies table to clients and add new fields
     */
    public function up(): void
    {
        // Rename table
        Schema::rename('agencies', 'clients');

        // Add new columns to clients table
        Schema::table('clients', function (Blueprint $table) {
            // Rename agency_name to name
            $table->renameColumn('agency_name', 'name');

            // Add WhatsApp number for this client's main contact
            $table->string('whatsapp_number', 20)->nullable()->after('name');

            // Service fee configuration
            $table->enum('service_fee_type', ['fixed', 'percentage'])->default('fixed')->after('logo_path');
            $table->decimal('service_fee_value', 10, 3)->default(0)->after('service_fee_type');
            $table->enum('service_fee_payer', ['agent', 'customer'])->default('agent')->after('service_fee_value');

            // WHMCS integration
            $table->unsignedBigInteger('whmcs_client_id')->nullable()->after('service_fee_payer');
            $table->enum('subscription_status', ['active', 'suspended', 'cancelled', 'trial'])->default('active')->after('whmcs_client_id');

            // Index for WHMCS lookup
            $table->index('whmcs_client_id');
        });

        // Update foreign key references in users table
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('agency_id', 'client_id');
        });

        // Update foreign key references in myfatoorah_credentials table
        Schema::table('myfatoorah_credentials', function (Blueprint $table) {
            $table->renameColumn('agency_id', 'client_id');
        });

        // Update foreign key references in webhook_configs table
        Schema::table('webhook_configs', function (Blueprint $table) {
            $table->renameColumn('agency_id', 'client_id');
        });

        // Update foreign key references in whatsapp_logs table
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->renameColumn('agency_id', 'client_id');
        });

        // Update foreign key references in activity_logs table
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->renameColumn('agency_id', 'client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert activity_logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->renameColumn('client_id', 'agency_id');
        });

        // Revert whatsapp_logs
        Schema::table('whatsapp_logs', function (Blueprint $table) {
            $table->renameColumn('client_id', 'agency_id');
        });

        // Revert webhook_configs
        Schema::table('webhook_configs', function (Blueprint $table) {
            $table->renameColumn('client_id', 'agency_id');
        });

        // Revert myfatoorah_credentials
        Schema::table('myfatoorah_credentials', function (Blueprint $table) {
            $table->renameColumn('client_id', 'agency_id');
        });

        // Revert users table
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('client_id', 'agency_id');
        });

        // Remove new columns from clients
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['whmcs_client_id']);
            $table->dropColumn([
                'whatsapp_number',
                'service_fee_type',
                'service_fee_value',
                'service_fee_payer',
                'whmcs_client_id',
                'subscription_status',
            ]);
            $table->renameColumn('name', 'agency_name');
        });

        // Rename back to agencies
        Schema::rename('clients', 'agencies');
    }
};
