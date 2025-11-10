<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // Local sqlite handled by other migration; nothing to do here.
            Log::info('Skipping enum update for sqlite.');
            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $database = DB::getDatabaseName();
            $row = DB::selectOne("
                SELECT COLUMN_TYPE as column_type, IS_NULLABLE as is_nullable, COLUMN_DEFAULT as column_default
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'transactions' AND COLUMN_NAME = 'type'
            ", [$database]);

            if (!$row) {
                Log::warning('Could not find transactions.type column info for MySQL.');
                return;
            }

            $columnType = $row->column_type;
            if (strpos($columnType, "'activation_fund'") !== false) {
                Log::info('activation_fund already exists in enum.');
                return;
            }

            if (preg_match("/^enum\((.*)\)$/", $columnType, $m)) {
                $vals = $m[1];
                $newVals = $vals . ", 'activation_fund'";

                $nullable = ($row->is_nullable === 'YES') ? 'NULL' : 'NOT NULL';
                $default = $row->column_default !== null ? "DEFAULT '" . $row->column_default . "'" : '';

                $sql = "ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM($newVals) $nullable $default";
                DB::statement($sql);
                Log::info('activation_fund added to enum for MySQL.');
            } else {
                Log::warning('transactions.type column_type not recognized: ' . $columnType);
            }

            return;
        }

        if ($driver === 'pgsql') {
            // For PostgreSQL: Use a check constraint for safety
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_type_check");

            DB::statement("
                ALTER TABLE transactions ADD CONSTRAINT transactions_type_check
                CHECK (type IN (
                    'deposit',
                    'withdrawal',
                    'transfer',
                    'interest',
                    'bonus',
                    'activation_fund'
                ))
            ");

            Log::info('activation_fund added to PostgreSQL check constraint.');
            return;
        }

        Log::warning('Unsupported DB driver: ' . $driver);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not remove enum values in down() to prevent production issues
        Log::info('Down migration skipped for activation_fund enum update.');
    }
};
