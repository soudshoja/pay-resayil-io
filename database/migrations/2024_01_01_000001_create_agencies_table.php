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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name');
            $table->string('iata_number', 50)->unique();
            $table->text('address')->nullable();
            $table->string('company_email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('timezone', 50)->default('Asia/Kuwait');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('iata_number');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
