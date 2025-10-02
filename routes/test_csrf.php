<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Test route to verify CSRF and sessions work
Route::get('/test-csrf', function (Request $request) {
    $sessionId = $request->session()->getId();
    $csrfToken = csrf_token();

    return response()->json([
        'message' => 'CSRF Test',
        'session_id' => $sessionId,
        'csrf_token' => $csrfToken,
        'session_driver' => config('session.driver'),
        'cookie_set' => $request->hasCookie(config('session.cookie')),
    ]);
})->name('test.csrf');

Route::post('/test-csrf-submit', function (Request $request) {
    return response()->json([
        'message' => 'CSRF verification passed!',
        'data' => $request->all(),
    ]);
})->name('test.csrf.submit');
