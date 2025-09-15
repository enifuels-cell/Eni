<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'receipt_code')) {
                $table->string('receipt_code', 16)->unique()->nullable()->after('id');
            }
        });

        // Backfill existing transactions with unique short codes
        $existing = DB::table('transactions')->whereNull('receipt_code')->select('id')->get();
        $used = DB::table('transactions')->pluck('receipt_code')->filter()->all();
        $usedMap = array_fill_keys($used, true);

        foreach ($existing as $row) {
            $code = null; $attempts = 0;
            do {
                $attempts++;
                $code = self::generateCode();
            } while (isset($usedMap[$code]) && $attempts < 10);
            $usedMap[$code] = true;
            DB::table('transactions')->where('id', $row->id)->update(['receipt_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'receipt_code')) {
                $table->dropColumn('receipt_code');
            }
        });
    }

    private static function generateCode(int $length = 8): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // excluded easily confused chars
        return collect(range(1, $length))->map(fn () => $alphabet[random_int(0, strlen($alphabet)-1)])->implode('');
    }
};
