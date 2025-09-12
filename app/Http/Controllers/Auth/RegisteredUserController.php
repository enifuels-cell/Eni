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
        $referralCode = $request->get('ref');
        return view('auth.register', compact('referralCode'));
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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'referral_code' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Handle referral if provided
        if ($request->referral_code) {
            // Try to find referrer by referral_code first
            $referrer = User::where('referral_code', $request->referral_code)->first();
            
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
