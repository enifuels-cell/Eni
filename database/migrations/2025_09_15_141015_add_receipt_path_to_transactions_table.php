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
        Schema::table('transactions', function (Blueprint $table) {
            // Check if receipt_path column doesn't exist before adding
            if (!Schema::hasColumn('transactions', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('receipt_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Check if receipt_path column exists before dropping
            if (Schema::hasColumn('transactions', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
        });
    }
};
