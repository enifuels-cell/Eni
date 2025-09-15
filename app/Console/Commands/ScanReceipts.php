<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class ScanReceipts extends Command
{
    protected $signature = 'receipts:scan {--fix-missing=} {--limit=}' ;
    protected $description = 'Scan transactions for receipt file consistency and report issues';

    public function handle(): int
    {
        $query = Transaction::whereNotNull('receipt_path');
        if ($limit = $this->option('limit')) {
            $query->limit((int)$limit);
        }

        $rows = $query->orderByDesc('id')->get();
        $disk = Storage::disk('local');
        $missing = 0; $ok = 0; $normalized = 0; $total = $rows->count();

        $this->info("Scanning {$total} transactions with receipts...");

        foreach ($rows as $t) {
            $original = $t->receipt_path;
            $path = ltrim($original, '/');
            if (str_starts_with($path, 'storage/')) { $path = substr($path, 8); }
            if (str_starts_with($path, 'public/')) {
                $alt = preg_replace('#^public/#','private/',$path);
                if ($disk->exists($alt)) { $path = $alt; }
            }
            if (!str_starts_with($path, 'private/')) {
                $candidate = 'private/' . $path;
                if ($disk->exists($candidate)) { $path = $candidate; }
            }

            $exists = $disk->exists($path);
            if ($exists) {
                $ok++;        
                if ($path !== $original) {
                    $normalized++;
                    // Optionally persist normalized path (disabled by default)
                    if ($this->option('fix-missing') === 'update-paths') {
                        $t->receipt_path = $path; $t->save();
                    }
                }
            } else {
                $missing++;
                $this->warn("Missing: txn {$t->id} path='{$original}' normalized='{$path}'");
            }
        }

        $this->line("OK: {$ok}  Missing: {$missing}  Normalized_different: {$normalized}");
        if ($missing > 0) {
            $this->comment('Tip: Ensure files are stored under storage/app/private or receipts, matching controller expectations.');
        }
        return self::SUCCESS;
    }
}
