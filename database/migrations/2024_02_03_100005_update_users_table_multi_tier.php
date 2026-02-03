<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update users table for multi-tier structure
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add visibility flag (platform owner shouldn't be visible to clients)
            $table->boolean('visible_to_clients')->default(true)->after('is_platform_owner');
        });

        // Update existing role values to new structure
        // Old roles: super_admin, admin, accountant, agent
        // New roles: platform_owner, client_admin, sales_person, accountant

        // Map admin -> client_admin for non-platform owners
        DB::table('users')
            ->where('role', 'admin')
            ->where('is_platform_owner', false)
            ->update(['role' => 'client_admin']);

        // Keep super_admin for compatibility but mark as platform role
        DB::table('users')
            ->where('role', 'super_admin')
            ->orWhere('is_platform_owner', true)
            ->update([
                'role' => 'platform_owner',
                'is_platform_owner' => true,
                'visible_to_clients' => false,
            ]);

        // Agent role will be deprecated - agents are now in separate table
        // Existing agents can be converted to sales_person or removed
        DB::table('users')
            ->where('role', 'agent')
            ->update(['role' => 'sales_person']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert role changes
        DB::table('users')
            ->where('role', 'platform_owner')
            ->update(['role' => 'super_admin']);

        DB::table('users')
            ->where('role', 'client_admin')
            ->update(['role' => 'admin']);

        DB::table('users')
            ->where('role', 'sales_person')
            ->update(['role' => 'agent']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('visible_to_clients');
        });
    }
};
