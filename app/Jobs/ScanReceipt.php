<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScanReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Transaction $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        // Ensure job is idempotent
        $this->onQueue('scans');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t = $this->transaction->fresh();
        if (!$t || !$t->receipt_path) {
            Log::warning('ScanReceipt: no transaction or no receipt_path', ['transaction_id' => $this->transaction->id ?? null]);
            return;
        }

        $path = storage_path('app/' . $t->receipt_path);
        if (!is_file($path)) {
            $t->update(['receipt_scan_status' => 'error']);
            Log::error('ScanReceipt: file not found for transaction', ['transaction_id' => $t->id, 'path' => $path]);
            return;
        }

        // Try clamdscan first (daemon), fallback to clamscan
        $scanner = null;
        $cmdVersion = null;
        $exitCode = null;
        $output = [];

        // prefer clamdscan
        exec('clamscan --version 2>&1', $tmpOut, $tmpCode);
        if ($tmpCode === 0) {
            $scanner = 'clamscan';
        } else {
            // try clamdscan
            exec('clamdscan --version 2>&1', $tmpOut2, $tmpCode2);
            if ($tmpCode2 === 0) {
                $scanner = 'clamdscan';
            }
        }

        if (!$scanner) {
            $t->update(['receipt_scan_status' => 'error']);
            Log::error('ScanReceipt: no ClamAV scanner found on PATH. Install clamscan or clamdscan.', ['transaction_id' => $t->id]);
            return;
        }

        // Run the scanner against the file
        $escaped = escapeshellarg($path);
        if ($scanner === 'clamdscan') {
            exec("clamdscan --no-summary {$escaped} 2>&1", $output, $exitCode);
        } else {
            exec("clamscan --no-summary {$escaped} 2>&1", $output, $exitCode);
        }

        // clamscan/clamdscan exit codes: 0 => clean, 1 => infected, >1 => error
        if ($exitCode === 0) {
            // File is clean. If it's an image, attempt to re-encode/sanitize using Intervention Image
            try {
                $mime = mime_content_type($path) ?: null;
            } catch (\Throwable $mErr) {
                $mime = null;
            }

            $isImage = $mime && str_starts_with($mime, 'image/');

            if ($isImage && class_exists(\Intervention\Image\ImageManagerStatic::class)) {
                try {
                    // Configure and re-encode the image to strip metadata and fix orientation
                    \Intervention\Image\ImageManagerStatic::configure(['driver' => 'gd']);
                    $img = \Intervention\Image\ImageManagerStatic::make($path);
                    $img->orientate(); // fix rotation based on EXIF

                    // Choose format by original extension
                    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($extension, ['jpg', 'jpeg'])) {
                        $img->encode('jpg', 85);
                    } else {
                        $img->encode('png', 90);
                    }

                    // Overwrite the file with the sanitized version
                    $img->save($path);

                    // Recompute metadata
                    $newMime = mime_content_type($path) ?: $mime;
                    $newSize = is_file($path) ? filesize($path) : null;
                    $newChecksum = is_file($path) ? hash_file('sha256', $path) : null;

                    $t->update([
                        'receipt_scan_status' => 'clean',
                        'receipt_mime' => $newMime,
                        'receipt_size' => $newSize,
                        'receipt_checksum' => $newChecksum,
                    ]);

                    Log::info('ScanReceipt: file clean and re-encoded', ['transaction_id' => $t->id]);
                    return;
                } catch (\Throwable $reErr) {
                    // If re-encode fails, mark clean but log the failure
                    $t->update(['receipt_scan_status' => 'clean']);
                    Log::warning('ScanReceipt: re-encode failed, marked clean', ['transaction_id' => $t->id, 'error' => $reErr->getMessage()]);
                    return;
                }
            }

            // Not an image or Intervention not available — mark as clean
            $t->update(['receipt_scan_status' => 'clean']);
            Log::info('ScanReceipt: file clean', ['transaction_id' => $t->id]);
            return;
        }

        if ($exitCode === 1) {
            // infected: remove file and mark infected
            try {
                Storage::disk('local')->delete($t->receipt_path);
            } catch (\Throwable $delErr) {
                Log::warning('ScanReceipt: failed to delete infected file', ['transaction_id' => $t->id, 'error' => $delErr->getMessage()]);
            }
            $t->update(['receipt_scan_status' => 'infected', 'is_flagged' => true]);
            Log::warning('ScanReceipt: file infected - removed and flagged', ['transaction_id' => $t->id]);

            // Notify admins via UserNotification records
            try {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    \App\Models\UserNotification::create([
                        'user_id' => $admin->id,
                        'title' => 'Infected receipt detected',
                        'message' => 'A receipt uploaded by user ID ' . $t->user_id . ' (transaction ID ' . $t->id . ') was detected as infected and has been flagged and removed.',
                        'category' => 'security',
                        'type' => 'infected_receipt',
                        'priority' => 'high',
                        'action_url' => route('admin.deposits.show', ['id' => $t->id]),
                        'is_read' => false,
                        'is_active' => true,
                    ]);
                }
            } catch (\Throwable $notifyErr) {
                Log::warning('ScanReceipt: failed to create admin notifications', ['error' => $notifyErr->getMessage(), 'transaction_id' => $t->id]);
            }
            return;
        }

        // Other exit codes => error
        $t->update(['receipt_scan_status' => 'error']);
        Log::error('ScanReceipt: scanner error', ['transaction_id' => $t->id, 'exit_code' => $exitCode, 'output' => $output]);
    }
}
