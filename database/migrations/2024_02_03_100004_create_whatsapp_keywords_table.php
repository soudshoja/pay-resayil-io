<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create WhatsApp keywords for triggering actions
     */
    public function up(): void
    {
        Schema::create('whatsapp_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // Keyword trigger (case-insensitive matching)
            $table->string('keyword', 100);

            // Action to perform
            $table->enum('action', [
                'payment_request',    // Generate payment link
                'balance_check',      // Check credit balance
                'status_check',       // Check payment status
                'help',               // Send help message
            ])->default('payment_request');

            // Optional response template
            $table->text('response_template')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index('client_id');
            $table->index(['keyword', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_keywords');
    }
};
