<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('user.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Determine what was updated for a more specific success message
        $bankFields = ['bank_name', 'account_number', 'account_holder_name', 'routing_number', 'swift_code'];
        $personalFields = ['name', 'email', 'phone', 'address', 'username'];
        
        $updatedBankFields = array_intersect(array_keys($validated), $bankFields);
        $updatedPersonalFields = array_intersect(array_keys($validated), $personalFields);
        
        if (!empty($updatedBankFields) && empty($updatedPersonalFields)) {
            $message = 'Bank details updated successfully!';
        } elseif (!empty($updatedPersonalFields) && empty($updatedBankFields)) {
            $message = 'Personal information updated successfully!';
        } else {
            $message = 'Profile updated successfully!';
        }

        return Redirect::route('profile.edit')->with('status', $message);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
