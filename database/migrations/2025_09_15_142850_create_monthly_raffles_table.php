<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_raffles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->default('Monthly iPhone Raffle'); // varchar can have default
            $table->text('description'); // TEXT cannot have default
            $table->year('raffle_year');
            $table->tinyInteger('raffle_month');
            $table->enum('status', ['active', 'drawn', 'cancelled'])->default('active');
            $table->unsignedBigInteger('winner_user_id')->nullable();
            $table->timestamp('drawn_at')->nullable();
            $table->json('draw_details')->nullable(); // JSON cannot have default
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_raffles');
    }
};
