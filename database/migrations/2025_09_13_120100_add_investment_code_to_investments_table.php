<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (!Schema::hasColumn('investments', 'investment_code')) {
                $table->string('investment_code', 12)->unique()->nullable()->after('id');
            }
        });

        // Backfill existing rows
        $existing = DB::table('investments')->whereNull('investment_code')->select('id')->get();
        $used = DB::table('investments')->pluck('investment_code')->filter()->all();
        $usedMap = array_fill_keys($used, true);

        foreach ($existing as $row) {
            $code = null; $attempts = 0;
            do {
                $attempts++;
                $code = 'INV-' . self::randSegment(6);
            } while (isset($usedMap[$code]) && $attempts < 10);
            $usedMap[$code] = true;
            DB::table('investments')->where('id', $row->id)->update(['investment_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            if (Schema::hasColumn('investments', 'investment_code')) {
                $table->dropColumn('investment_code');
            }
        });
    }

    private static function randSegment(int $length): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $seg = '';
        for ($i=0; $i<$length; $i++) {
            $seg .= $alphabet[random_int(0, strlen($alphabet)-1)];
        }
        return $seg;
    }
};
