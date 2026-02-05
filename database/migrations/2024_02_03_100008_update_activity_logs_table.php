<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update activity_logs for multi-tier structure
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add agent reference
            $table->foreignId('agent_id')->nullable()->after('client_id')->constrained()->onDelete('set null');

            // Visibility control (platform-only actions shouldn't show to clients)
            $table->boolean('visible_to_clients')->default(true)->after('user_agent');

            // Index for agent lookup
            $table->index('agent_id');
            $table->index('visible_to_clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropIndex(['agent_id']);
            $table->dropIndex(['visible_to_clients']);

            $table->dropColumn(['agent_id', 'visible_to_clients']);
        });
    }
};
