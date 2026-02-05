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
        Schema::create('myfatoorah_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->unique()->constrained('agencies')->onDelete('cascade');
            $table->text('api_key')->comment('Encrypted MyFatoorah API key');
            $table->string('country_code', 3)->default('KWT');
            $table->boolean('is_test_mode')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_verified_at')->nullable();
            $table->json('supported_methods')->nullable()->comment('Cached payment methods');
            $table->timestamps();

            $table->index('agency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myfatoorah_credentials');
    }
};
