<?php

namespace Tests\Feature\Integrity;

use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeUniquenessStressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This test intentionally creates a relatively large batch of records to ensure
     * generated short codes (receipt_code, investment_code) remain unique under load.
     * Adjust TOTAL_* constants if performance becomes an issue in CI.
     */
    private const TOTAL_TRANSACTIONS = 1200; // adjust as needed
    private const TOTAL_INVESTMENTS  = 800;

    public function test_generated_codes_are_unique_under_load(): void
    {
    $user = User::factory()->create();
    // Pre-create a single investment package to be reused by investment factory logic
    \App\Models\InvestmentPackage::factory()->create();

        // Create transactions
        $transactionCodes = [];
        for ($i = 0; $i < self::TOTAL_TRANSACTIONS; $i++) {
            $t = Transaction::factory()->create(['user_id' => $user->id]);
            $code = $t->receipt_code;
            $this->assertNotEmpty($code, 'Transaction code should not be empty');
            $transactionCodes[] = $code;
        }

        // Create investments
        $investmentCodes = [];
        for ($i = 0; $i < self::TOTAL_INVESTMENTS; $i++) {
            $inv = Investment::factory()->create(['user_id' => $user->id]);
            $code = $inv->investment_code;
            $this->assertNotEmpty($code, 'Investment code should not be empty');
            $investmentCodes[] = $code;
        }

        $this->assertSameSize(array_unique($transactionCodes), $transactionCodes, 'Duplicate receipt_code detected');
        $this->assertSameSize(array_unique($investmentCodes), $investmentCodes, 'Duplicate investment_code detected');
    }
}
