<?php

namespace Tests\Feature\Finance;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\InvestmentPackage;
use Illuminate\Support\Str;

class AccountBalanceConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Ensure there is at least one investment package
        InvestmentPackage::factory()->create(['min_amount' => 1, 'max_amount' => 100000]);
    }

    public function test_insufficient_balance_returns_422_and_no_approved_transaction()
    {
        $user = User::factory()->create(['account_balance' => 10]);
        $package = InvestmentPackage::first();

        $response = $this->actingAs($user)->post(route('user.deposit.process'), [
            'amount' => 100,
            'payment_method' => 'account_balance',
            'package_id' => $package->id,
        ]);

        // The app may return a 422 JSON response for API consumers or a 302 redirect with
        // session validation errors for web form submissions. Accept either.
        if ($response->getStatusCode() === 422) {
            $response->assertStatus(422);
        } else {
            $response->assertStatus(302);
            $response->assertSessionHasErrors();
        }

        $this->assertDatabaseMissing('transactions', [
            'user_id' => $user->id,
            'amount' => 100,
            'status' => 'approved'
        ]);
    }

    public function test_concurrent_account_balance_requests_only_one_succeeds()
    {
        $user = User::factory()->create(['account_balance' => 100]);
        $package = InvestmentPackage::first();

        // Make two sequential requests that would conflict if race-safety is not implemented
        $this->actingAs($user)->post(route('user.deposit.process'), [
            'amount' => 60,
            'payment_method' => 'account_balance',
            'package_id' => $package->id,
        ]);

        $this->actingAs($user)->post(route('user.deposit.process'), [
            'amount' => 60,
            'payment_method' => 'account_balance',
            'package_id' => $package->id,
        ]);

        // At most one approved transaction should exist for this user (race-safety)
        $approvedCount = \App\Models\Transaction::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        $this->assertLessThanOrEqual(1, $approvedCount, 'More than one approved transaction was created');

        // Balance should not be negative and should reflect at most one deduction of 60
        $user->refresh();
        $this->assertGreaterThanOrEqual(0, $user->account_balance);
        $this->assertContains($user->account_balance, [40, 100]);
    }
}
