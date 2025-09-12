<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use App\Mail\WelcomeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Handle referral parameter with support for username, referral_code, or user_id
        $referrerUser = null;
        $referralParam = $request->get('ref');
        
        if ($referralParam) {
            \Log::info("Referral parameter detected: " . $referralParam);
            
            // First try to find by username (new system - most user-friendly)
            $referrerUser = User::where('username', $referralParam)->first();
            
            // If not found, try by referral_code (current system)
            if (!$referrerUser) {
                $referrerUser = User::where('referral_code', $referralParam)->first();
            }
            
            // If still not found, try by user ID for backward compatibility
            if (!$referrerUser && is_numeric($referralParam)) {
                $referrerUser = User::find($referralParam);
                \Log::info("Fallback: Looking up user by ID: " . $referralParam);
            }
            
            if ($referrerUser) {
                \Log::info("Found referrer user: " . $referrerUser->name . " (ID: " . $referrerUser->id . ", Username: " . ($referrerUser->username ?? 'none') . ")");
            } else {
                \Log::warning("No referrer found for parameter: " . $referralParam);
            }
        }
        
        // Debug logging
        \Log::info('Registration page accessed', [
            'ref_parameter' => $referralParam,
            'referrer_found' => $referrerUser ? $referrerUser->name : 'none',
            'all_parameters' => $request->all(),
            'query_string' => $request->getQueryString()
        ]);
        
        return view('auth.register', [
            'referralCode' => $referralParam,
            'referrerUser' => $referrerUser
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9_]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'referral_code' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Handle referral if provided
        if ($request->referral_code) {
            // Try to find referrer by username first (new system - most user-friendly)
            $referrer = User::where('username', $request->referral_code)->first();
            
            // If not found by username, try by referral_code 
            if (!$referrer) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
            }
            
            // If not found by referral_code, try by user ID for backward compatibility
            if (!$referrer && is_numeric($request->referral_code)) {
                $referrer = User::find($request->referral_code);
            }
            
            if ($referrer && $referrer->id !== $user->id) {
                // Create referral record
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referee_id' => $user->id,
                    'referral_code' => $request->referral_code,
                    'referred_at' => now(),
                ]);
                
                \Log::info('Referral created', [
                    'referrer_id' => $referrer->id,
                    'referee_id' => $user->id,
                    'referral_code' => $request->referral_code
                ]);
            } else {
                \Log::warning('Referral creation failed', [
                    'referral_code' => $request->referral_code,
                    'referrer_found' => $referrer ? 'yes' : 'no',
                    'referee_id' => $user->id
                ]);
            }
        }

        event(new Registered($user));

        // Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            \Log::info('Welcome email sent', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
