<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'receipt_mime')) {
                $table->string('receipt_mime')->nullable()->after('receipt_path');
            }
            if (!Schema::hasColumn('transactions', 'receipt_size')) {
                $table->integer('receipt_size')->nullable()->after('receipt_mime');
            }
            if (!Schema::hasColumn('transactions', 'receipt_checksum')) {
                $table->string('receipt_checksum', 64)->nullable()->after('receipt_size');
            }
            if (!Schema::hasColumn('transactions', 'receipt_scan_status')) {
                $table->string('receipt_scan_status')->nullable()->default('pending')->after('receipt_checksum');
            }
            if (!Schema::hasColumn('transactions', 'receipt_uploaded_at')) {
                $table->timestamp('receipt_uploaded_at')->nullable()->after('receipt_scan_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'receipt_uploaded_at')) {
                $table->dropColumn('receipt_uploaded_at');
            }
            if (Schema::hasColumn('transactions', 'receipt_scan_status')) {
                $table->dropColumn('receipt_scan_status');
            }
            if (Schema::hasColumn('transactions', 'receipt_checksum')) {
                $table->dropColumn('receipt_checksum');
            }
            if (Schema::hasColumn('transactions', 'receipt_size')) {
                $table->dropColumn('receipt_size');
            }
            if (Schema::hasColumn('transactions', 'receipt_mime')) {
                $table->dropColumn('receipt_mime');
            }
        });
    }
};
