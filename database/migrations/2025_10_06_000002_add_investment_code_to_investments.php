<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('investments', 'investment_code')) {
            Schema::table('investments', function (Blueprint $table) {
                $table->string('investment_code', 12)->nullable()->after('id');
            });
        }

        // Create unique index if not exists (SQLite doesn't support constraints via ALTER easily)
        try {
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS investments_investment_code_unique ON investments(investment_code)');
        } catch (\Throwable $e) {
            // ignore if index creation fails for DB engines that don't support IF NOT EXISTS or it's already present
        }

        // Backfill existing null codes
        $existing = DB::table('investments')->whereNull('investment_code')->select('id')->get();
        $used = DB::table('investments')->pluck('investment_code')->filter()->all();
        $usedMap = array_fill_keys($used, true);

        foreach ($existing as $row) {
            $code = null; $attempts = 0;
            do {
                $attempts++;
                $code = 'INV-' . $this->randSegment(6);
            } while (isset($usedMap[$code]) && $attempts < 20);
            $usedMap[$code] = true;
            DB::table('investments')->where('id', $row->id)->update(['investment_code' => $code]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('investments', 'investment_code')) {
            Schema::table('investments', function (Blueprint $table) {
                $table->dropColumn('investment_code');
            });
        }

        try {
            DB::statement('DROP INDEX IF EXISTS investments_investment_code_unique');
        } catch (\Throwable $e) {
            // ignore
        }
    }

    private function randSegment(int $length): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $seg = '';
        for ($i = 0; $i < $length; $i++) {
            $seg .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return $seg;
    }
};
