<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        // Check if user has PIN device cookie and should use PIN login
        $pinDevice = $request->cookie('pin_device');

        if ($pinDevice) {
            try {
                $deviceData = decrypt($pinDevice);
                $user = \App\Models\User::find($deviceData['user_id']);

                if ($user && $user->pin_hash) {
                    return redirect()->route('pin.login.form');
                }
            } catch (\Exception $e) {
                // Invalid cookie, clear it and continue to regular login
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('pin_device'));
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        // Redirect admin users to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Check if user doesn't have PIN set up and prompt them
        if (!$user->pin_hash) {
            session()->flash('pin_setup_prompt', true);
            session()->flash('success', 'Welcome! Set up a 4-digit PIN for faster login next time.');
        }

        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // If user has PIN set, create device cookie for future PIN login
        if ($user && $user->pin_hash) {
            $deviceData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'device_id' => $this->getDeviceFingerprint($request)
            ];

            Cookie::queue('pin_device', encrypt($deviceData), 60 * 24 * 30); // 30 days
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Generate device fingerprint for security
     */
    private function getDeviceFingerprint(Request $request)
    {
        $components = [
            $request->userAgent(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
            $request->ip()
        ];

        return hash('sha256', implode('|', $components));
    }
}
