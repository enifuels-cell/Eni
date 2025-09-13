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
            abort(403);
        }

        if (!$transaction->receipt_path) {
            \Log::channel('investment')->warning('Receipt missing path', ['transaction_id' => $transaction->id]);
            abort(404);
        }

        $disk = Storage::disk('local');

        // Normalize stored path: allow prefixes like 'private/' or leading slashes
        $path = ltrim($transaction->receipt_path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        // If user-form uploads previously stored under public/ move expectation to private/
        if (str_starts_with($path, 'public/')) {
            $alt = preg_replace('#^public/#', 'private/', $path);
            if ($alt && $disk->exists($alt)) {
                $path = $alt;
            }
        }
        if (!str_starts_with($path, 'private/')) {
            // Force into private directory convention if file exists there
            $candidate = 'private/' . $path;
            if ($disk->exists($candidate)) {
                $path = $candidate;
            }
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
                'X-Receipt-Code' => $transaction->receipt_code ?? ''
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
            'X-Receipt-Code' => $transaction->receipt_code ?? ''
        ]);
    }
}
