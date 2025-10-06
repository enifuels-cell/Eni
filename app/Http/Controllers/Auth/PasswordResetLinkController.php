<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        // Attempt to send the password reset link via mail. If mail fails (for
        // example in local env due to SMTP misconfiguration), generate a reset
        // token and provide a direct link for developers to use.
        $status = null;
        $user = User::where('email', $request->input('email'))->first();

        // In local/testing environments, don't rely on SMTP — generate a dev link immediately.
        if (app()->environment('local', 'testing')) {
            if ($user) {
                try {
                    $token = Password::broker()->createToken($user);
                    $link = url(route('password.reset', $token, false)) . '?email=' . urlencode($user->email);
                    return back()->with('status', 'Password reset link generated for local testing.')->with('dev_reset_link', $link);
                } catch (\Throwable $e) {
                    Log::warning('Failed to create local password reset token: ' . $e->getMessage());
                }
            }
            // fallthrough to attempt normal send for non-existing user
        }

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::warning('Password reset email failed: ' . $e->getMessage());
            $status = null;
        }

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // In local/testing, generate a dev link even if sending failed or throttled
        if (app()->environment('local', 'testing') && $user) {
            try {
                $token = Password::broker()->createToken($user);
                $link = url(route('password.reset', $token, false)) . '?email=' . urlencode($user->email);
                return back()->with('status', 'Password reset link generated for local testing.')->with('dev_reset_link', $link);
            } catch (\Throwable $e) {
                Log::warning('Failed to create local password reset token: ' . $e->getMessage());
            }
        }

        $errorMessage = $status ? __($status) : 'Failed to send password reset link. Please contact support.';
        return back()->withInput($request->only('email'))->withErrors(['email' => $errorMessage]);
    }
}
