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
        Schema::create('daily_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->integer('tickets_earned')->default(1);
            $table->time('first_login_time');
            $table->timestamp('logged_in_at');
            $table->timestamps();
            
            // Ensure one attendance record per user per day
            $table->unique(['user_id', 'attendance_date']);
            $table->index(['attendance_date']);
            $table->index(['user_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_attendance');
    }
};
