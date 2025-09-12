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
        Schema::table('user_notifications', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('user_notifications', 'category')) {
                $table->string('category')->default('system')->after('message');
            }
            if (!Schema::hasColumn('user_notifications', 'type')) {
                $table->string('type')->default('info')->after('category');
            }
            if (!Schema::hasColumn('user_notifications', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('type');
            }
            if (!Schema::hasColumn('user_notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('user_notifications', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_read');
            }
            if (!Schema::hasColumn('user_notifications', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('is_active');
            }
        });

        // Indexes are already created in the original migration, skip adding them here
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'category', 'type', 'priority', 'action_url', 'is_active', 'expires_at'
            ]);
        });
    }
};
