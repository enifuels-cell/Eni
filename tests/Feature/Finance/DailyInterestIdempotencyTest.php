<?php

namespace Tests\Feature\Finance;

use App\Console\Commands\UpdateTotalInterest;
use App\Models\DailyInterestLog;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DailyInterestIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_interest_command_is_idempotent_for_same_day(): void
    {
        $user = User::factory()->create();
        $package = InvestmentPackage::factory()->create([
            'daily_shares_rate' => 1.5, // percent per day
            'effective_days' => 30,
            'min_amount' => 100,
            'max_amount' => 10000,
        ]);

        $investment = Investment::factory()->create([
            'user_id' => $user->id,
            'investment_package_id' => $package->id,
            'amount' => 1000.00,
            'active' => true,
            'started_at' => now()->subDay(),
            'remaining_days' => 30,
        ]);

        // First run
    Artisan::call('interest:update', ['--dry-run' => false]);
        $logsAfterFirst = DailyInterestLog::where('investment_id', $investment->id)->count();
        $transactionsAfterFirst = $user->transactions()->where('type', 'interest')->count();

        // Second run same day
    Artisan::call('interest:update', ['--dry-run' => false]);
        $logsAfterSecond = DailyInterestLog::where('investment_id', $investment->id)->count();
        $transactionsAfterSecond = $user->transactions()->where('type', 'interest')->count();

        $this->assertEquals(1, $logsAfterFirst, 'Exactly one interest log should be created on first run');
        $this->assertEquals($logsAfterFirst, $logsAfterSecond, 'No additional interest log on second run');
        $this->assertEquals(1, $transactionsAfterFirst, 'Exactly one interest transaction should be created');
        $this->assertEquals($transactionsAfterFirst, $transactionsAfterSecond, 'No duplicate interest transaction');
    }
}
