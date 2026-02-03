<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update payment_requests for multi-tier structure
     */
    public function up(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            // Note: agency_id was already renamed to client_id in the first migration

            // Add agent reference (the travel agency making the request)
            $table->foreignId('agent_id')->nullable()->after('client_id')->constrained()->onDelete('set null');

            // Service fee tracking
            $table->decimal('service_fee', 10, 3)->default(0)->after('amount');
            $table->decimal('total_amount', 10, 3)->nullable()->after('service_fee');

            // Internal notes (visible to platform/client only)
            $table->text('internal_note')->nullable()->after('description');

            // Confirmation tracking
            $table->timestamp('confirmed_at')->nullable()->after('paid_at');
            $table->foreignId('confirmed_by_user_id')->nullable()->after('confirmed_at')->constrained('users')->onDelete('set null');

            // Index for agent lookup
            $table->index('agent_id');
        });

        // Calculate total_amount for existing records
        \DB::statement('UPDATE payment_requests SET total_amount = amount + service_fee WHERE total_amount IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['confirmed_by_user_id']);
            $table->dropIndex(['agent_id']);

            $table->dropColumn([
                'agent_id',
                'service_fee',
                'total_amount',
                'internal_note',
                'confirmed_at',
                'confirmed_by_user_id',
            ]);

            // Note: client_id rename handled in first migration
        });
    }
};
