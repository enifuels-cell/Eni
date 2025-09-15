<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReceiptController extends Controller
{
    public function show(Transaction $transaction, Request $request)
    {
        $user = $request->user();
        if (!$user || $transaction->user_id !== $user->id) {
            \Log::channel('investment')->warning('Unauthorized receipt access attempt', [
                'transaction_id' => $transaction->id,
                'acting_user_id' => $user?->id,
                'owner_user_id' => $transaction->user_id,
                'ip' => $request->ip(),
            ]);
            \App\Services\AuditLogger::log($user, 'receipt.unauthorized_access', $transaction, [
                'owner_user_id' => $transaction->user_id,
            ]);
            abort(403);
        }

        if (!$transaction->receipt_path) {
            \Log::channel('investment')->warning('Receipt missing path', ['transaction_id' => $transaction->id]);
            abort(404);
        }

        $disk = Storage::disk('local');

        // --- Path Hardening & Normalization ---
        $originalPath = $transaction->receipt_path;
        $path = ltrim($originalPath, '/');

        // Disallow absolute drive letters or protocol wrappers (Windows / streams)
        if (preg_match('/^[A-Za-z]:\\\\|^[a-z]+:\/\//i', $path)) {
            \Log::channel('investment')->warning('Rejected receipt path with disallowed prefix', [
                'transaction_id' => $transaction->id,
                'path' => $originalPath,
            ]);
            abort(404);
        }

        // Normalize legacy prefixes
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        if (str_starts_with($path, 'public/')) {
            $candidate = preg_replace('#^public/#', 'private/', $path);
            if ($candidate) {
                $path = $candidate;
            }
        }

        // Enforce private root
        if (!str_starts_with($path, 'private/')) {
            $path = 'private/' . $path;
        }

        // Collapse any repeated slashes
        $path = preg_replace('#/{2,}#', '/', $path);

        // Reject traversal attempts after normalization
        if (str_contains($path, '..')) {
            \Log::channel('investment')->warning('Rejected receipt path traversal attempt', [
                'transaction_id' => $transaction->id,
                'normalized' => $path,
                'original' => $originalPath,
                'ip' => $request->ip(),
            ]);
            abort(404);
        }

        if (!$disk->exists($path)) {
            $debug = [
                'original' => $transaction->receipt_path,
                'normalized' => $path,
                'candidates' => array_filter([
                    $transaction->receipt_path,
                    'private/' . ltrim($transaction->receipt_path, '/'),
                    preg_replace('#^public/#', 'private/', ltrim($transaction->receipt_path, '/')),
                ]),
            ];
            \Log::channel('investment')->warning('Receipt file not found', $debug);
            if ($request->boolean('debug')) {
                return response()->json(['error' => 'not_found', 'debug' => $debug], 404);
            }
            abort(404);
        }
        
        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $filename = basename($path);

        // Inline display for images / pdf, else download
        $inline = preg_match('/^(image\/(jpeg|png)|application\/pdf)$/', $mime) === 1;

        if ($inline) {
            return new StreamedResponse(function () use ($disk, $path) {
                $stream = $disk->readStream($path);
                if (is_resource($stream)) {
                    fpassthru($stream);
                    fclose($stream);
                } else {
                    echo $disk->get($path);
                }
            }, 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
                'X-Receipt-Code' => $transaction->receipt_code ?? '',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY'
            ]);
        }

        return response()->streamDownload(function () use ($disk, $path) {
            $stream = $disk->readStream($path);
            if (is_resource($stream)) {
                fpassthru($stream);
                fclose($stream);
            } else {
                echo $disk->get($path);
            }
        }, $filename, [
            'Content-Type' => $mime,
            'X-Receipt-Code' => $transaction->receipt_code ?? '',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY'
        ]);
    }
}
