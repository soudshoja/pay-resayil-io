<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create authorized phones for agents (for WhatsApp identification)
     */
    public function up(): void
    {
        Schema::create('agent_authorized_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');

            // Phone in E.164 format (+96599800027)
            $table->string('phone_number', 20);
            $table->string('full_name')->nullable();

            // Verification status
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->unique('phone_number'); // Each phone can only belong to one agent
            $table->index('agent_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_authorized_phones');
    }
};
