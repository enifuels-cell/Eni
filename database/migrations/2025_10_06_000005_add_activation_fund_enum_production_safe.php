<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // Local sqlite handled by other migration; nothing to do here for production-safe file.
            logger()->info('Skipping enum update for sqlite in production-safe migration.');
            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            // Fetch current enum definition
            $database = DB::getDatabaseName();
            $row = DB::selectOne(
                "SELECT COLUMN_TYPE as column_type, IS_NULLABLE as is_nullable, COLUMN_DEFAULT as column_default FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'transactions' AND COLUMN_NAME = 'type'",
                [$database]
            );

            if (!$row) {
                logger()->warning('Could not find transactions.type column info for MySQL.');
                return;
            }

            $columnType = $row->column_type; // e.g. enum('deposit','withdrawal',...)
            if (strpos($columnType, "'activation_fund'") !== false) {
                // already present
                return;
            }

            // strip leading enum( and trailing )
            if (preg_match("/^enum\((.*)\)$/", $columnType, $m)) {
                $vals = $m[1];
                $newVals = $vals . ", 'activation_fund'";

                $nullable = ($row->is_nullable === 'YES') ? 'NULL' : 'NOT NULL';
                $default = $row->column_default !== null ? "DEFAULT '" . $row->column_default . "'" : '';

                // Alter the column to include the new enum value
                $sql = "ALTER TABLE `transactions` MODIFY COLUMN `type` ENUM($newVals) $nullable $default";
                DB::statement($sql);
            } else {
                logger()->warning('transactions.type column_type not recognized: ' . $columnType);
            }

            return;
        }

        if ($driver === 'pgsql') {
            // Determine enum type name for the column
            $typeRow = DB::selectOne(
                "SELECT t.typname AS enum_type FROM pg_type t JOIN pg_attribute a ON a.atttypid = t.oid JOIN pg_class c ON a.attrelid = c.oid WHERE c.relname = 'transactions' AND a.attname = 'type' AND t.typtype = 'e'"
            );

            if (!$typeRow || empty($typeRow->enum_type)) {
                logger()->warning('Could not determine enum type name for transactions.type');
                return;
            }

            $enumType = $typeRow->enum_type;

            // Use a DO block to add value only if it doesn't exist
            $safeSql = <<<'SQL'
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM pg_enum e JOIN pg_type t ON e.enumtypid = t.oid
        WHERE e.enumlabel = 'activation_fund' AND t.typname = '%s'
    ) THEN
        EXECUTE format('ALTER TYPE "%s" ADD VALUE %L', '%s', 'activation_fund');
    END IF;
END$$;
SQL;

            // Inject the enum type name safely
            $safeSql = sprintf($safeSql, $enumType, $enumType, $enumType);
            DB::statement($safeSql);
            return;
        }

        logger()->warning('Unsupported DB driver for adding activation_fund enum: ' . $driver);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Removing enum values is dangerous and DB-specific; do not attempt to remove in down migration.
        logger()->info('Down migration skipped for add_activation_fund_enum_production_safe (enum removal not performed).');
    }
};
