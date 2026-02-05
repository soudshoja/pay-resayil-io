<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create transaction notes for internal communication
     */
    public function up(): void
    {
        Schema::create('transaction_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');

            // Note content
            $table->text('note');

            // Visibility control
            $table->boolean('visible_to_clients')->default(true);

            // Type of note
            $table->enum('note_type', [
                'general',
                'status_update',
                'issue',
                'resolution',
                'internal',
            ])->default('general');

            $table->timestamps();

            // Indexes
            $table->index('payment_request_id');
            $table->index('created_by_user_id');
            $table->index('visible_to_clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_notes');
    }
};
