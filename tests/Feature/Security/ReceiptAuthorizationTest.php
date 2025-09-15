<?php

namespace Tests\Feature\Security;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReceiptAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_receipt(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        // Fake a stored private receipt file
        Storage::fake('local');
        $path = 'private/receipts/test-demo.png';
        Storage::disk('local')->put($path, 'dummy');

        $transaction = Transaction::factory()->create([
            'user_id' => $owner->id,
            'type' => 'deposit',
            'amount' => 100.00,
            'status' => 'completed',
            'receipt_path' => $path,
        ]);

        $response = $this->actingAs($intruder)->get('/transaction/'.$transaction->id.'/receipt-file');

        $response->assertStatus(403); // Expect forbidden
    }
}
