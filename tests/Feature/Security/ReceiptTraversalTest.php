<?php

namespace Tests\Feature\Security;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReceiptTraversalTest extends TestCase
{
    use RefreshDatabase;

    public function test_path_traversal_attempt_is_rejected(): void
    {
        $user = User::factory()->create();
        Storage::fake('local');

        // Create a legitimate transaction with a safe path
        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'receipt_path' => 'private/receipts/safe-file.png',
            'type' => 'deposit',
            'amount' => 50,
            'status' => 'completed'
        ]);
        Storage::disk('local')->put('private/receipts/safe-file.png', 'dummy');

        // Tamper model (simulate if path were manipulated earlier) with traversal sequence
        $transaction->receipt_path = '../.env';
        $transaction->save();

        $response = $this->actingAs($user)->get('/transaction/'.$transaction->id.'/receipt-file');
        $response->assertStatus(404); // Treated as not found, not exposed
    }
}
