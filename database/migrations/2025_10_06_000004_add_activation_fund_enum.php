<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'sqlite') {
            // Non-sqlite environments may need manual enum ALTER statements.
            logger()->info('Skipping enum migration for transactions.type on non-sqlite driver: ' . $driver);
            return;
        }

        // For SQLite we recreate the table with the new CHECK constraint including activation_fund
        Schema::create('transactions_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'transfer', 'interest', 'referral_bonus', 'other', 'activation_fund']);
            $table->decimal('amount', 15, 2);
            $table->string('reference')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable()->index();
            $table->string('receipt_path')->nullable();
            $table->string('receipt_code')->nullable();
            $table->timestamps();
        });

        // Copy data from old table to new table (map columns that exist)
        $columns = [
            'id','user_id','type','amount','reference','status','description','processed_at','created_at','updated_at'
        ];

        // If processed_by exists in old table, include it
        $oldColumns = array_map('strtolower', DB::select('PRAGMA table_info(transactions)') ? array_map(function($c){return $c->name;}, DB::select('PRAGMA table_info(transactions)')) : []);
        if (in_array('processed_by', $oldColumns)) {
            $columns[] = 'processed_by';
        }

        // Build column lists for SQL
        $colsList = implode(', ', $columns);

        DB::statement("INSERT INTO transactions_new ($colsList) SELECT $colsList FROM transactions;");

        Schema::drop('transactions');
        Schema::rename('transactions_new', 'transactions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible for sqlite; skip.
        $driver = DB::getDriverName();
        if ($driver !== 'sqlite') {
            logger()->info('Skipping reverse enum migration for transactions.type on non-sqlite driver: ' . $driver);
        }
    }
};
