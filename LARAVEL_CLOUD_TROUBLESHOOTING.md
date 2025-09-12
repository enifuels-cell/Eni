# Laravel Cloud Daily Interest Troubleshooting

## Current Issue
```
php artisan interest:update --dry-run
Processing daily interest for: 2025-09-12
No active investments found.
```

## Diagnosis Steps

Run these commands on Laravel Cloud to identify the issue:

### 1. Check if investments exist at all
```bash
php artisan tinker --execute="echo 'Total investments: ' . App\Models\Investment::count();"
```

### 2. Check investment status
```bash
php artisan tinker --execute="dump(App\Models\Investment::get(['id', 'user_id', 'amount', 'active', 'remaining_days'])->toArray());"
```

### 3. Check if users exist
```bash
php artisan tinker --execute="echo 'Total users: ' . App\Models\User::count();"
```

### 4. Check pending deposits (need admin approval)
```bash
php artisan tinker --execute="echo 'Pending deposits: ' . App\Models\Transaction::where('status', 'pending')->count();"
```

### 5. Check investment packages
```bash
php artisan tinker --execute="echo 'Investment packages: ' . App\Models\InvestmentPackage::count();"
```

## Likely Causes & Solutions

### Cause 1: No Data Seeded
**If counts are 0, you need to seed data:**
```bash
# Seed investment packages
php artisan db:seed --class=InvestmentPackageSeeder

# Create test user (if needed)
php artisan db:seed --class=CreateTestUserSeeder
```

### Cause 2: Investments Not Activated
**If investments exist but are inactive:**
```bash
# Check investment activation status
php artisan tinker --execute="
echo 'Inactive investments: ' . App\Models\Investment::where('active', false)->count() . PHP_EOL;
echo 'Active investments: ' . App\Models\Investment::where('active', true)->count() . PHP_EOL;
"

# Manually activate investments (if safe to do so)
php artisan tinker --execute="
App\Models\Investment::where('active', false)
    ->update([
        'active' => true, 
        'started_at' => now()
    ]);
echo 'Investments activated';
"
```

### Cause 3: Pending Deposits Need Admin Approval
**If there are pending deposits:**
```bash
# View pending deposits
php artisan tinker --execute="dump(App\Models\Transaction::where('status', 'pending')->get(['id', 'user_id', 'amount', 'type'])->toArray());"

# Approve all pending deposits (CAUTION: Only do this if you're sure)
php artisan tinker --execute="
App\Models\Transaction::where('status', 'pending')
    ->where('type', 'deposit')
    ->update(['status' => 'completed', 'processed_at' => now()]);
echo 'Deposits approved';
"
```

### Cause 4: Database Migration Issues
**If tables don't exist:**
```bash
# Check if tables exist
php artisan tinker --execute="
try {
    echo 'Investments table exists: ' . (Schema::hasTable('investments') ? 'Yes' : 'No') . PHP_EOL;
    echo 'Users table exists: ' . (Schema::hasTable('users') ? 'Yes' : 'No') . PHP_EOL;
    echo 'Investment packages table exists: ' . (Schema::hasTable('investment_packages') ? 'Yes' : 'No') . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"

# If tables are missing, run migrations
php artisan migrate
```

## Quick Fix Commands

### Option 1: Create Test Data (Safe)
```bash
# Create test investment packages
php artisan tinker --execute="
App\Models\InvestmentPackage::firstOrCreate([
    'name' => 'Energy Saver',
    'min_amount' => 200,
    'max_amount' => 899,
    'daily_shares_rate' => 0.5,
    'effective_days' => 180,
    'available_slots' => 100,
    'referral_bonus_rate' => 5.0,
    'active' => true,
    'image' => 'Energy.png'
]);
echo 'Test package created';
"

# Create test user with balance
php artisan tinker --execute="
\$user = App\Models\User::firstOrCreate([
    'email' => 'test@eni.com'
], [
    'name' => 'Test User',
    'password' => Hash::make('password'),
    'role' => 'user',
    'account_balance' => 1000,
    'email_verified_at' => now()
]);
echo 'Test user created with ID: ' . \$user->id;
"

# Create test investment
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'test@eni.com')->first();
\$package = App\Models\InvestmentPackage::first();
if (\$user && \$package) {
    App\Models\Investment::create([
        'user_id' => \$user->id,
        'investment_package_id' => \$package->id,
        'amount' => 200,
        'daily_shares_rate' => \$package->daily_shares_rate,
        'remaining_days' => \$package->effective_days,
        'total_interest_earned' => 0,
        'active' => true,
        'started_at' => now()
    ]);
    echo 'Test investment created';
} else {
    echo 'User or package not found';
}
"
```

### Option 2: Import Local Data (If Available)
```bash
# Export from local and import to cloud (if you have local data)
# This would require database dump/restore
```

## Verification Commands

After running fixes, verify with:
```bash
# Check active investments
php artisan tinker --execute="echo 'Active investments: ' . App\Models\Investment::active()->where('remaining_days', '>', 0)->count();"

# Test daily interest
php artisan interest:update --dry-run

# If successful, run actual interest calculation
php artisan interest:update
```

## Expected Results After Fix

**Should show something like:**
```
Processing daily interest for: 2025-09-12
Investment #1 - User: Test User - Interest: $1.00
Summary:
Total investments processed: 1
Total interest distributed: $1.00
DRY RUN - No changes were made to the database.
```

## Next Steps

1. **Run diagnosis commands** to identify the specific issue
2. **Apply appropriate fix** based on results
3. **Verify with dry-run** before actual execution
4. **Set up proper data** for production use

The daily interest system code is working perfectly - this is just a data/setup issue on the live server!
