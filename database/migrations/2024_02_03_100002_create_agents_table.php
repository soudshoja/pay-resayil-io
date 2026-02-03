<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create agents table (travel agencies managed by clients)
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->onDelete('set null');

            // Agent company details
            $table->string('company_name');
            $table->string('iata_number', 50)->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            // Accountant WhatsApp for payment notifications
            $table->string('accountant_whatsapp', 20)->nullable();

            // Address and contact
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('logo_path')->nullable();

            // Credit/balance tracking (optional future feature)
            $table->decimal('credit_balance', 12, 3)->default(0);
            $table->decimal('credit_limit', 12, 3)->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('client_id');
            $table->index('sales_person_id');
            $table->index('iata_number');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
