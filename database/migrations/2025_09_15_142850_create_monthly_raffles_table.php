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
        Schema::create('monthly_raffles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Monthly iPhone Raffle');
            $table->text('description')->default('Win a brand new iPhone Air!');
            $table->year('raffle_year');
            $table->tinyInteger('raffle_month'); // 1-12
            $table->enum('status', ['active', 'drawn', 'cancelled'])->default('active');
            $table->foreignId('winner_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('drawn_at')->nullable();
            $table->json('draw_details')->nullable(); // Store draw mechanics details
            $table->timestamps();
            
            $table->unique(['raffle_year', 'raffle_month']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_raffles');
    }
};
