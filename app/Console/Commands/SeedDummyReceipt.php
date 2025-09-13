<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;

class SeedDummyReceipt extends Command
{
    protected $signature = 'demo:seed-receipt {--user-id=}';
    protected $description = 'Create a dummy transaction with a fake receipt file for testing secure streaming.';

    public function handle(): int
    {
        $userId = $this->option('user-id');
        $user = $userId ? User::find($userId) : User::first();
        if (!$user) {
            $user = User::factory()->create();
            $this->info('Created test user with ID '.$user->id.' (email: '.$user->email.')');
        }

        $dir = 'private/receipts';
        $disk = Storage::disk('local');
        if (!$disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $filename = 'demo-receipt-'.Str::random(6).'.png';
        $path = $dir.'/'.$filename;

        // Tiny 1x1 transparent PNG
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGP4//8/AwAI/AL+Z2hs4QAAAABJRU5ErkJggg==');
        $disk->put($path, $pngData);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 100,
            'reference' => 'DEMO-'.Str::upper(Str::random(6)),
            'status' => 'pending',
            'description' => 'Demo deposit with dummy receipt',
            'receipt_path' => $path,
        ]);

        $this->line('Created transaction ID '.$transaction->id.' with receipt path '.$path);
        $this->line('Test URL (must be logged in as this user): '.url('/transaction/'.$transaction->id.'/receipt-file'));

        return self::SUCCESS;
    }
}
