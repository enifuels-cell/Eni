<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'receipt_code')) {
                // already added earlier, skip
            }
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
        });

        Schema::table('investments', function (Blueprint $table) {
            if (!Schema::hasColumn('investments', 'investment_code')) {
                // skip - handled earlier
            }
            $table->index(['user_id', 'active']);
        });

        Schema::table('referral_bonuses', function (Blueprint $table) {
            // composite uniqueness to prevent duplicates for same referral/investment
            $table->unique(['referral_id', 'investment_id']);
        });

        Schema::table('daily_interest_logs', function (Blueprint $table) {
            // ensure single interest log per investment per date
            if (!Schema::hasColumn('daily_interest_logs', 'interest_date')) {
                // If schema lacks date column, we can't add uniqueness—skip.
            } else {
                $table->unique(['investment_id', 'interest_date']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['transactions_user_id_type_index']);
            $table->dropIndex(['transactions_user_id_status_index']);
        });
        Schema::table('investments', function (Blueprint $table) {
            $table->dropIndex(['investments_user_id_active_index']);
        });
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->dropUnique(['referral_bonuses_referral_id_investment_id_unique']);
        });
        Schema::table('daily_interest_logs', function (Blueprint $table) {
            if (Schema::hasColumn('daily_interest_logs', 'interest_date')) {
                $table->dropUnique(['daily_interest_logs_investment_id_interest_date_unique']);
            }
        });
    }
};
