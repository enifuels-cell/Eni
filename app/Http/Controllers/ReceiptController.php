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
        if (!$disk->exists($transaction->receipt_path)) {
            \Log::channel('investment')->warning('Receipt file not found', ['path' => $transaction->receipt_path]);
            abort(404);
        }

        $mime = $disk->mimeType($transaction->receipt_path) ?: 'application/octet-stream';
        $filename = basename($transaction->receipt_path);

        // Inline display for images / pdf, else download
        $inline = preg_match('/^(image\/(jpeg|png)|application\/pdf)$/', $mime) === 1;

        return new StreamedResponse(function () use ($disk, $transaction) {
            echo $disk->get($transaction->receipt_path);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="'.$filename.'"',
            'X-Receipt-Code' => $transaction->receipt_code ?? ''
        ]);
    }
}
