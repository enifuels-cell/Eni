# PIN Login Fix - Device Cookie Issue

## Problem Identified

The PIN login was not working because the device cookie (`pin_device`) was not being properly set and persisted across browser sessions. When users logged in with their password after setting up a PIN, the cookie wasn't being attached to the response correctly.

## Root Cause

The code was using `Cookie::queue()` which queues cookies to be sent with the next response, but this doesn't always work reliably, especially when redirecting. The cookie needs to be explicitly attached to the redirect response using `->cookie()`.

## Changes Made

### 1. **AuthenticatedSessionController.php** - Regular Login

- **Before**: Used `Cookie::queue()` to set the PIN device cookie
- **After**: Now uses `Cookie::make()` and attaches it directly to the redirect response with `->cookie($cookie)`
- **Lines Changed**:
  - Regular login flow now properly sets cookie when user has PIN configured
  - Logout flow now properly preserves cookie for PIN users

### 2. **PinLoginController.php** - PIN Setup & Login

- **Before**: Used `Cookie::queue()` for cookie creation
- **After**: Now uses `Cookie::make()` and attaches to response with `->cookie($cookie)`
- **Lines Changed**:
  - `setupPin()` method now properly sets cookie after PIN creation
  - `loginWithPin()` method now refreshes cookie after successful PIN login

## How It Works Now

### First Time Flow

1. User logs in with email/password
2. System prompts user to set up a PIN
3. User sets up a 4-digit PIN
4. **Cookie is created and attached to response** → `pin_device` cookie is set for 30 days
5. On next visit, user is automatically redirected to PIN login screen

### Returning User Flow

1. User opens website
2. System checks for `pin_device` cookie
3. If cookie exists and is valid:
   - User is redirected to PIN login screen (instead of regular login)
   - User enters 4-digit PIN
   - System validates PIN and logs user in
   - **Cookie is refreshed** for another 30 days

### Logout Flow

1. User clicks logout
2. Session is destroyed
3. **Cookie is preserved** (not deleted) so PIN login remains available
4. User is redirected to login page
5. System detects `pin_device` cookie and shows PIN login screen

## Cookie Details

**Cookie Name**: `pin_device`

**Cookie Data** (encrypted):

```php
[
    'user_id' => 123,
    'email' => 'user@example.com',
    'device_id' => 'sha256_hash_of_device_fingerprint'
]
```

**Cookie Duration**: 30 days (43,200 minutes)

**Security Features**:

- Data is encrypted using Laravel's encryption
- Device fingerprint includes: User Agent, Accept-Language, Accept-Encoding, IP Address
- PIN is hashed with bcrypt (never stored in plain text)
- Rate limiting: 5 attempts per 5 minutes
- Weak PINs are rejected (0000, 1111, 1234, etc.)

## Testing Instructions

### Test 1: First Time PIN Setup

1. Clear your browser cookies
2. Log in with email/password
3. Go to Profile → Setup PIN
4. Enter a 4-digit PIN (e.g., 2468) and confirm
5. Click "Set Up PIN Login"
6. Log out
7. ✅ **Expected**: You should see the PIN login screen (not the regular login)

### Test 2: PIN Login

1. From the PIN login screen
2. Enter your 4-digit PIN
3. ✅ **Expected**: You should be logged in and redirected to home

### Test 3: Cookie Persistence

1. Log in with PIN
2. Close browser completely
3. Open browser again and visit the website
4. ✅ **Expected**: You should see the PIN login screen (not regular login)

### Test 4: Switch to Regular Login

1. From PIN login screen
2. Click "Use Password Instead" (if available)
3. ✅ **Expected**: You should see the regular login form

### Test 5: Invalid PIN

1. From PIN login screen
2. Enter wrong PIN 3 times
3. ✅ **Expected**: Error message appears, rate limiting activates after 5 attempts

## Browser Developer Tools Check

To verify the cookie is set correctly:

1. Open Developer Tools (F12)
2. Go to "Application" or "Storage" tab
3. Find "Cookies" → Your domain
4. Look for `pin_device` cookie
5. ✅ Should show:
   - **Name**: `pin_device`
   - **Value**: Encrypted string (long random characters)
   - **Expires**: Date 30 days from now
   - **HttpOnly**: Yes
   - **Secure**: Depends on HTTPS
   - **SameSite**: Lax

## Troubleshooting

### Issue: Cookie not appearing in browser

**Solution**: Clear all caches and try again:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Issue: Still showing regular login instead of PIN login

**Possible Causes**:

1. Cookie was not set properly → Log in again and check Developer Tools
2. Cookie expired (30 days passed) → Set up PIN again
3. Browser blocking cookies → Check browser privacy settings
4. Different device/browser → Cookie is device-specific

### Issue: "Invalid cookie" error

**Solution**:

1. Clear browser cookies
2. Log in with email/password
3. Set up PIN again

### Issue: Cookie keeps getting deleted

**Check**:

1. Browser is not in Incognito/Private mode
2. Browser is not set to clear cookies on close
3. No browser extensions blocking cookies
4. Session configuration in `.env` is correct

## Security Notes

- PIN must be exactly 4 digits
- Weak PINs are rejected (sequential, repeated numbers)
- PIN is hashed with bcrypt (same security as passwords)
- Device fingerprint ensures cookie works only on the same device
- Rate limiting prevents brute force attacks (5 attempts per 5 minutes)
- Cookie data is encrypted
- HttpOnly flag prevents JavaScript access

## Environment Variables

Make sure these are set in `.env`:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_EXPIRE_ON_CLOSE=false
SESSION_ENCRYPT=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

## Next Steps

1. Test the PIN login flow thoroughly
2. Monitor cookie creation in browser DevTools
3. Test across different browsers (Chrome, Firefox, Edge, Safari)
4. Test on mobile devices
5. Ensure HTTPS is enabled on production (cookies work better with HTTPS)

---

**Fix Applied**: October 2, 2025  
**Status**: ✅ Ready for Testing
