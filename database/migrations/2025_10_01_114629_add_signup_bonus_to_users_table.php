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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('signup_bonus_claimed')->default(false)->after('account_balance');
            $table->timestamp('signup_bonus_claimed_at')->nullable()->after('signup_bonus_claimed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['signup_bonus_claimed', 'signup_bonus_claimed_at']);
        });
    }
};
