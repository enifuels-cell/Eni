<?php

namespace Tests\Feature\Audit;

use App\Models\Transaction;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuditLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_creates_audit_log(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(10),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'user.verified_email'
        ]);
    }

    public function test_unauthorized_receipt_access_is_audited(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $transaction = Transaction::factory()->create([
            'user_id' => $owner->id,
            'receipt_path' => 'private/receipts/demo.png',
            'type' => 'deposit',
            'amount' => 10.00,
            'status' => 'completed'
        ]);

        $this->actingAs($intruder)->get('/transaction/'.$transaction->id.'/receipt-file');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'receipt.unauthorized_access',
            'subject_type' => Transaction::class,
            'subject_id' => $transaction->id,
        ]);
    }
}
