<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('agent_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('myfatoorah_invoice_id', 100)->nullable()->index();
            $table->string('myfatoorah_payment_id', 100)->nullable()->index();
            $table->text('payment_url')->nullable();
            $table->decimal('amount', 10, 3);
            $table->string('currency', 3)->default('KWD');
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('myfatoorah_response')->nullable();
            $table->timestamp('webhook_received_at')->nullable();
            $table->string('reference_id', 100)->nullable();
            $table->string('track_id', 100)->nullable();
            $table->timestamps();

            $table->index('agency_id');
            $table->index('status');
            $table->index('customer_phone');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
