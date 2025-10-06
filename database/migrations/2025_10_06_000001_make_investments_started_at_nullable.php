<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = config('database.default');

        // Attempt a safe change for non-sqlite drivers
        if ($connection !== 'sqlite') {
            try {
                Schema::table('investments', function (Blueprint $table) {
                    $table->timestamp('started_at')->nullable()->change();
                });
                return;
            } catch (\Throwable $e) {
                // Fall through to sqlite-style rebuild logic if change() is unsupported
            }
        }

        // For sqlite (or if change() fails), recreate table preserving data
        DB::statement('PRAGMA foreign_keys=off');

        // Create new table with nullable started_at
        DB::statement(<<<SQL
            CREATE TABLE investments_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                user_id INTEGER NOT NULL,
                investment_package_id INTEGER NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                daily_shares_rate DECIMAL(5,2) NOT NULL,
                remaining_days INTEGER NOT NULL,
                total_interest_earned DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                active TINYINT(1) NOT NULL DEFAULT 1,
                started_at DATETIME NULL,
                ended_at DATETIME NULL,
                investment_code VARCHAR(64) NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            );
        SQL
        );

        // Copy data from old table into new table
        DB::statement(<<<SQL
            INSERT INTO investments_new (id, user_id, investment_package_id, amount, daily_shares_rate, remaining_days, total_interest_earned, active, started_at, ended_at, investment_code, created_at, updated_at)
            SELECT id, user_id, investment_package_id, amount, daily_shares_rate, remaining_days, total_interest_earned, active, started_at, ended_at, investment_code, created_at, updated_at FROM investments;
        SQL
        );

        // Drop old table and rename new table
        Schema::drop('investments');
        DB::statement('ALTER TABLE investments_new RENAME TO investments');

        // Recreate foreign key constraints (if any) - handled by other migrations normally
        DB::statement('PRAGMA foreign_keys=on');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('database.default');

        if ($connection !== 'sqlite') {
            try {
                Schema::table('investments', function (Blueprint $table) {
                    $table->timestamp('started_at')->nullable(false)->change();
                });
                return;
            } catch (\Throwable $e) {
                // Fall through
            }
        }

        // For sqlite, recreate table with NOT NULL started_at (best-effort)
        DB::statement('PRAGMA foreign_keys=off');

        DB::statement(<<<SQL
            CREATE TABLE investments_old (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                user_id INTEGER NOT NULL,
                investment_package_id INTEGER NOT NULL,
                amount DECIMAL(15,2) NOT NULL,
                daily_shares_rate DECIMAL(5,2) NOT NULL,
                remaining_days INTEGER NOT NULL,
                total_interest_earned DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                active TINYINT(1) NOT NULL DEFAULT 1,
                started_at DATETIME NOT NULL,
                ended_at DATETIME NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            );
        SQL
        );

        // Copy data, filling started_at with created_at if NULL
        DB::statement(<<<SQL
            INSERT INTO investments_old (id, user_id, investment_package_id, amount, daily_shares_rate, remaining_days, total_interest_earned, active, started_at, ended_at, created_at, updated_at)
            SELECT id, user_id, investment_package_id, amount, daily_shares_rate, remaining_days, total_interest_earned, active, COALESCE(started_at, created_at), ended_at, created_at, updated_at FROM investments;
        SQL
        );

        Schema::drop('investments');
        DB::statement('ALTER TABLE investments_old RENAME TO investments');

        DB::statement('PRAGMA foreign_keys=on');
    }
};
