<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PinLoginController extends Controller
{
    /**
     * Show the PIN login form
     */
    public function showPinLoginForm(Request $request)
    {
        $pinDevice = $request->cookie('pin_device');
        
        if (!$pinDevice) {
            return redirect()->route('login');
        }

        try {
            $deviceData = decrypt($pinDevice);
            $user = User::find($deviceData['user_id']);
            
            if (!$user || !$user->pin_hash) {
                // Clear invalid cookie
                Cookie::queue(Cookie::forget('pin_device'));
                return redirect()->route('login');
            }

            return view('auth.pin-login', [
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Cookie::queue(Cookie::forget('pin_device'));
            return redirect()->route('login');
        }
    }

    /**
     * Handle PIN login attempt
     */
    public function loginWithPin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pin' => 'required', // Accept either string or array
        ]);

        $userId = $request->user_id;
        
        // Handle both string and array PIN formats
        if (is_array($request->pin)) {
            // Old format: array of individual digits
            $pin = implode('', $request->pin);
        } else {
            // New format: single string
            $pin = $request->pin;
        }
        
        // Validate PIN format
        if (!preg_match('/^\d{4}$/', $pin)) {
            throw ValidationException::withMessages([
                'pin' => ['PIN must be exactly 4 digits.'],
            ]);
        }

        // Rate limiting
        $key = 'pin-login:' . $userId . ':' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'pin' => ['Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.'],
            ]);
        }

        $user = User::find($userId);
        
        if (!$user || !$user->pin_hash || !Hash::check($pin, $user->pin_hash)) {
            RateLimiter::hit($key, 300); // 5 minute lockout
            
            throw ValidationException::withMessages([
                'pin' => ['Invalid PIN. Please try again.'],
            ]);
        }

        // Check if user is suspended
        if ($user->isSuspended()) {
            RateLimiter::hit($key, 300); // Rate limit suspended users too
            
            throw ValidationException::withMessages([
                'pin' => ['Your account has been suspended. Please contact administrator.'],
            ]);
        }

        // Clear rate limiter on successful login
        RateLimiter::clear($key);

        // Log the user in
        Auth::login($user, true);

        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Set up PIN for authenticated user
     */
    public function setupPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4|confirmed',
        ], [
            'pin.digits' => 'PIN must be exactly 4 digits.',
            'pin.confirmed' => 'PIN confirmation does not match.'
        ]);

        $user = Auth::user();
        
        // Check for common/weak PINs
        $weakPins = ['0000', '1111', '2222', '3333', '4444', '5555', '6666', '7777', '8888', '9999', '1234', '4321', '1122', '2211'];
        
        if (in_array($request->pin, $weakPins)) {
            throw ValidationException::withMessages([
                'pin' => ['Please choose a more secure PIN. Avoid sequential or repeated numbers.'],
            ]);
        }

        $user->update([
            'pin_hash' => Hash::make($request->pin),
            'pin_set_at' => now()
        ]);

        // Set device cookie for future PIN logins
        $deviceData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'device_id' => $this->getDeviceFingerprint($request)
        ];

        Cookie::queue('pin_device', encrypt($deviceData), 60 * 24 * 30); // 30 days

        return redirect()->route('dashboard')->with('success', 'PIN setup completed successfully!');
    }

    /**
     * Remove PIN from user account
     */
    public function removePin(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();
        $user->update([
            'pin_hash' => null,
            'pin_set_at' => null
        ]);

        // Clear device cookie
        Cookie::queue(Cookie::forget('pin_device'));

        return redirect()->route('profile.edit')->with('success', 'PIN removed successfully.');
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
