# Fix: Admin Approve Deposit - Money Object Issue

## Problem
Admin could not approve user deposits. When clicking "Approve", it returned a **500 error** with "Something went wrong".

## Root Cause
The `approveDeposit` method in `AdminDashboardController.php` was trying to use `$transaction->amount` directly with `increment()` and `decrement()` methods. 

However, the `Transaction` model uses `MoneyCast` for the `amount` field, which converts it to a `Money` value object (not a plain number).

```php
// Transaction model - app/Models/Transaction.php
protected function casts(): array
{
    return [
        'amount' => \App\Casts\MoneyCast::class,  // Returns Money object, not float!
        ...
    ];
}
```

When Laravel tried to increment/decrement with a Money object, it caused a type error.

## Solution
Updated `approveDeposit()` method to convert Money objects to float values before using them with increment/decrement:

```php
// Get the amount as a decimal number (Money object to float)
$amountValue = $transaction->amount instanceof \App\Support\Money 
    ? $transaction->amount->toFloat()  // Convert Money to float
    : (float) $transaction->amount;     // Or just cast to float

// Now we can safely increment
$transaction->user->increment('account_balance', $amountValue);
```

## Files Changed
- `app/Http/Controllers/Admin/AdminDashboardController.php`
  - Line ~125: Added Money to float conversion for deposit amount
  - Line ~158: Added Money to float conversion for investment amount
  - Added try-catch block with detailed error logging

## Testing
1. ✅ Clear caches: `php artisan config:clear && php artisan cache:clear`
2. ✅ Admin can now approve deposits without 500 error
3. ✅ User balance updates correctly
4. ✅ Investment activates and slots decrement
5. ✅ Error logging added for debugging future issues

## Related
- `app/Support/Money.php` - Money value object with `toFloat()` method
- `app/Casts/MoneyCast.php` - Cast that converts DB values to Money objects
- `app/Models/Transaction.php` - Uses MoneyCast for amount field
- `app/Models/Investment.php` - Also uses MoneyCast for amount field

## Prevention
When working with monetary amounts:
1. Check if the model uses `MoneyCast` for the field
2. If yes, convert to float: `$amount->toFloat()`
3. Or use Money arithmetic: `$money->add($otherMoney)`
4. Never pass Money objects directly to increment/decrement

## Date Fixed
October 3, 2025
