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
            // Add account balance if it doesn't exist
            if (!Schema::hasColumn('users', 'account_balance')) {
                $table->decimal('account_balance', 15, 2)->default(0.00)->after('email_verified_at');
            }
            
            // Add username if it doesn't exist
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->after('name');
            }
            
            // Add role if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin'])->default('user')->after('account_balance');
            }
            
            // Add last_login_at if it doesn't exist
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            }
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            // Add receipt path if it doesn't exist
            if (!Schema::hasColumn('transactions', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_balance', 'username', 'role']);
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('receipt_path');
        });
    }
};
