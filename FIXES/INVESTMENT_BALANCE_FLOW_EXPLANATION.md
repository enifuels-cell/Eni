# Investment Balance vs Account Balance - Flow Explanation

## 📊 Balance Structure (CORRECT Implementation)

### Account Balance (Withdrawable Funds)
The **Account Balance** should ONLY contain funds that users can withdraw:
- ✅ Daily interest earnings from active investments
- ✅ Referral bonuses from referred users
- ✅ Regular deposits (not for investment)
- ❌ NOT investment principal (that's locked)

### Total Invested (Locked Funds)
The **Total Invested** shows all active investment principals:
- ✅ Sum of all active investment amounts
- ✅ Locked for the investment period
- ✅ Cannot be withdrawn until maturity
- ✅ Earns daily interest

---

## 🔄 User Journey Flow

### Scenario 1: User Invests in a Package

```
┌─────────────────────────────────────────────────────────────┐
│  STEP 1: User Creates Investment                            │
│  ----------------------------------------------------------- │
│  User selects "Growth Power" package: ₱5,000                │
│  Creates bank transfer deposit for ₱5,000                   │
│  Status: Pending admin approval                             │
│                                                              │
│  Account Balance: ₱0                                        │
│  Total Invested: ₱0                                         │
│  Active Investments: 0                                      │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 2: Admin Approves Investment Deposit                  │
│  ----------------------------------------------------------- │
│  Admin clicks "Approve" on ₱5,000 deposit                   │
│  System does:                                               │
│  ✅ Marks transaction as "approved"                         │
│  ✅ Activates investment → started_at = now()              │
│  ✅ Decrements package slots by 1                           │
│  ✅ Funds go DIRECTLY to locked investment                  │
│  ❌ Does NOT add to account_balance                         │
│                                                              │
│  Account Balance: ₱0 (no change!)                           │
│  Total Invested: ₱5,000 (locked)                            │
│  Active Investments: 1                                      │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 3: Daily Interest Calculation (Next Day)              │
│  ----------------------------------------------------------- │
│  Cron job runs: php artisan interest:update                 │
│  Growth Power: 0.7% daily × ₱5,000 = ₱35/day               │
│  System does:                                               │
│  ✅ Creates daily_interest_log record                       │
│  ✅ Adds ₱35 to user's account_balance                      │
│  ✅ Updates investment.total_interest_earned                │
│                                                              │
│  Account Balance: ₱35 (withdrawable!)                       │
│  Total Invested: ₱5,000 (still locked)                      │
│  Total Interest Earned: ₱35                                 │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 4: After 30 Days of Interest                          │
│  ----------------------------------------------------------- │
│  30 days × ₱35/day = ₱1,050 total interest                  │
│                                                              │
│  Account Balance: ₱1,050 (can withdraw)                     │
│  Total Invested: ₱5,000 (still locked)                      │
│  Total Interest Earned: ₱1,050                              │
│  Days Remaining: 150 days (180-day package)                 │
└─────────────────────────────────────────────────────────────┘
```

### Scenario 2: User Refers a Friend

```
┌─────────────────────────────────────────────────────────────┐
│  STEP 1: Friend Signs Up with Referral Code                 │
│  ----------------------------------------------------------- │
│  Friend uses user's referral code                           │
│  Friend makes ₱10,000 investment                            │
│  Admin approves friend's investment                         │
│                                                              │
│  System calculates:                                         │
│  Referral bonus: 7% × ₱10,000 = ₱700                        │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│  STEP 2: Referral Bonus Credited                            │
│  ----------------------------------------------------------- │
│  System does:                                               │
│  ✅ Creates referral_bonus record                           │
│  ✅ Adds ₱700 to user's account_balance                     │
│  ✅ Marks bonus as paid                                     │
│                                                              │
│  Account Balance: ₱1,750 (₱1,050 + ₱700)                    │
│  Total Invested: ₱5,000                                     │
│  Total Referral Bonus: ₱700                                 │
└─────────────────────────────────────────────────────────────┘
```

---

## 📈 Dashboard Display

```
┌─────────────────────────────────────────────────────────────┐
│  USER DASHBOARD                                              │
│  ─────────────────────────────────────────────────────────  │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │ Total        │  │ Total        │  │ Referral     │     │
│  │ Invested     │  │ Interest     │  │ Bonus        │     │
│  │              │  │              │  │              │     │
│  │ ₱5,000       │  │ ₱1,050       │  │ ₱700         │     │
│  │              │  │              │  │              │     │
│  │ (Locked)     │  │ (Earned)     │  │ (Earned)     │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                              │
│  ┌──────────────────────────────────────────────────┐      │
│  │ Account Balance (Withdrawable)                   │      │
│  │                                                   │      │
│  │         ₱1,750                                    │      │
│  │                                                   │      │
│  │ = ₱1,050 (interest) + ₱700 (referral)           │      │
│  └──────────────────────────────────────────────────┘      │
│                                                              │
│  [Invest More]  [Withdraw Funds]  [View Transactions]      │
└─────────────────────────────────────────────────────────────┘
```

---

## 💰 What Goes Into Account Balance?

### ✅ SHOULD Be in Account Balance (Withdrawable)
1. **Daily Interest Earnings**
   - From active investments
   - Calculated by `php artisan interest:update`
   - Added daily to account_balance

2. **Referral Bonuses**
   - From friends who invest using your code
   - % of their investment amount
   - Added when their investment is approved

3. **Regular Deposits**
   - Manual deposits NOT for investment
   - Top-up balance for future use
   - Immediately available

4. **Matured Investment Returns**
   - When investment reaches maturity (180 days)
   - Principal + remaining interest released
   - Becomes withdrawable

### ❌ SHOULD NOT Be in Account Balance
1. **Active Investment Principal**
   - Goes to `investments.amount`
   - Locked until maturity
   - Shown in "Total Invested"

2. **Pending Deposits**
   - Awaiting admin approval
   - Not yet credited anywhere
   - Shown in transactions as "pending"

---

## 🔧 Code Implementation

### Before (WRONG - Current Issue):
```php
// Admin approves investment deposit
$transaction->user->increment('account_balance', $amount);  // ❌ WRONG
// Then later...
$transaction->user->decrement('account_balance', $amount);  // This cancels out!
```

**Problem**: Money goes to account_balance then immediately comes out. User sees balance flash up then disappear.

### After (CORRECT - Fixed):
```php
// Admin approves investment deposit
if ($isInvestmentDeposit) {
    // Activate investment DIRECTLY - funds locked
    $investment->update(['active' => true, 'started_at' => now()]);
    // ✅ NO change to account_balance
} else {
    // Regular deposit - add to withdrawable balance
    $transaction->user->increment('account_balance', $amount);
}
```

**Result**: Investment funds go straight to locked investment. Account balance stays clean.

---

## 🎯 Summary

| Balance Type | Contains | Withdrawable? | Updates When |
|-------------|----------|---------------|--------------|
| **Account Balance** | Interest + Bonuses + Regular deposits | ✅ YES | Daily (interest), Instant (bonuses) |
| **Total Invested** | Sum of all active investment principals | ❌ NO (locked) | New investment approved |
| **Total Interest** | Cumulative interest from all investments | N/A (info only) | Daily calculation |
| **Referral Bonus** | Cumulative bonuses from referrals | N/A (info only) | Friend invests |

---

## ✅ User Experience

### What Users See Now (CORRECT):

1. **After investing ₱5,000:**
   - Account Balance: ₱0 ✅ (makes sense - money is locked)
   - Total Invested: ₱5,000 ✅ (shows their investment)
   - Message: "Investment active - earning 0.7% daily"

2. **Next day:**
   - Account Balance: ₱35 ✅ (today's interest - can withdraw)
   - Total Invested: ₱5,000 ✅ (still locked)
   - Total Interest: ₱35 ✅

3. **They can:**
   - ✅ Withdraw the ₱35 (their earnings)
   - ❌ Cannot withdraw the ₱5,000 (locked for 180 days)
   - ✅ See clear separation between earnings and investment

---

## 🚀 Benefits of This Approach

1. **Clear Separation**
   - Users know what they can withdraw (account balance)
   - vs what's locked (total invested)

2. **Accurate Reporting**
   - Account balance = true withdrawable amount
   - No confusion about "where did my money go?"

3. **Prevents Errors**
   - Can't accidentally withdraw locked investment funds
   - System enforces maturity periods

4. **Better UX**
   - Users see their earnings grow daily
   - Clear visualization of locked vs liquid funds

---

**Date**: October 3, 2025  
**Status**: ✅ IMPLEMENTED CORRECTLY
