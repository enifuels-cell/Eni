# Laravel Cloud Deployment Commands for Daily Interest System

## 1. Initial Setup Commands (Run Once After Deployment)

### Database Setup
```bash
# Run database migrations
php artisan migrate

# Seed investment packages (if needed)
php artisan db:seed --class=InvestmentPackageSeeder
```

### Verify System Components
```bash
# Check if investments exist and are active
php artisan tinker --execute="dump(App\Models\Investment::active()->count());"

# Check if daily interest command is available
php artisan list | grep interest
```

## 2. Manual Daily Interest Execution

### Test the System (Safe - No Changes)
```bash
# Dry run to see what would be processed
php artisan interest:update --dry-run
```

### Execute Daily Interest Calculation
```bash
# Run daily interest calculation manually
php artisan interest:update
```

## 3. Automated Scheduling Setup

### Option A: Laravel Cloud Native Scheduling
Laravel Cloud should automatically handle the scheduler if you have this in your `routes/console.php`:
```php
Schedule::command('interest:update')->daily();
```

### Option B: Manual Cron Job (If Laravel Cloud Doesn't Auto-Schedule)
Add this cron job to run Laravel's scheduler:
```bash
# Edit crontab
crontab -e

# Add this line (replace /path/to/your/project with actual path)
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

## 4. Verification Commands

### Check if Interest Was Processed Today
```bash
# Check daily interest logs for today
php artisan tinker --execute="use Carbon\Carbon; dump(App\Models\DailyInterestLog::whereDate('interest_date', Carbon::today())->count());"

# Check user balances
php artisan tinker --execute="dump(App\Models\User::where('account_balance', '>', 0)->get(['id', 'name', 'account_balance'])->toArray());"

# Check today's interest transactions
php artisan tinker --execute="use Carbon\Carbon; dump(App\Models\Transaction::where('type', 'interest')->whereDate('created_at', Carbon::today())->sum('amount'));"
```

### Check Active Investments
```bash
# Verify active investments
php artisan tinker --execute="dump(App\Models\Investment::active()->where('remaining_days', '>', 0)->count());"
```

## 5. Troubleshooting Commands

### If No Active Investments
```bash
# Check if investments need activation (pending deposits)
php artisan tinker --execute="dump(App\Models\Transaction::where('status', 'pending')->count());"

# Manually activate investments (if needed)
php artisan tinker --execute="App\Models\Investment::where('active', false)->update(['active' => true, 'started_at' => now()]);"
```

### Force Interest Calculation (Emergency Use Only)
```bash
# This will process interest even if already done today (use carefully)
php artisan tinker --execute="
use Carbon\Carbon;
App\Models\DailyInterestLog::whereDate('interest_date', Carbon::today())->delete();
"
php artisan interest:update
```

## 6. Production Deployment Checklist

### Essential Files to Verify
- ✅ `routes/console.php` contains: `Schedule::command('interest:update')->daily();`
- ✅ `app/Console/Commands/UpdateTotalInterest.php` exists
- ✅ Database migrations are applied
- ✅ Investment packages are seeded

### Post-Deployment Test Sequence
```bash
# 1. Check system status
php artisan interest:update --dry-run

# 2. If investments are ready, run once manually
php artisan interest:update

# 3. Verify results
php artisan tinker --execute="
use Carbon\Carbon;
echo 'Today Interest Logs: ' . App\Models\DailyInterestLog::whereDate('interest_date', Carbon::today())->count() . PHP_EOL;
echo 'Total Interest Today: $' . App\Models\Transaction::where('type', 'interest')->whereDate('created_at', Carbon::today())->sum('amount') . PHP_EOL;
"
```

## 7. Monitoring Commands (Run Periodically)

### Daily Health Check
```bash
# Check if yesterday's interest was processed
php artisan tinker --execute="
use Carbon\Carbon;
\$yesterday = Carbon::yesterday();
\$processed = App\Models\DailyInterestLog::whereDate('interest_date', \$yesterday)->count();
echo 'Yesterday (' . \$yesterday->toDateString() . ') processed: ' . \$processed . ' investments' . PHP_EOL;
"
```

### Weekly Report
```bash
# Get weekly interest summary
php artisan tinker --execute="
use Carbon\Carbon;
\$weekAgo = Carbon::now()->subWeek();
\$total = App\Models\Transaction::where('type', 'interest')->where('created_at', '>=', \$weekAgo)->sum('amount');
echo 'Total interest paid in last 7 days: $' . \$total . PHP_EOL;
"
```

## Important Notes

1. **First Run**: May show 0 investments if deposits aren't approved by admin
2. **Timing**: Interest calculates based on server timezone
3. **Duplicate Prevention**: Running twice same day will show 0 processed (this is correct)
4. **Scheduler**: Laravel Cloud should auto-detect the scheduler, but verify it's working after deployment

## Emergency Recovery
If daily interest stops working:
```bash
# Check what's wrong
php artisan interest:update --dry-run

# Check for errors in logs
tail -f storage/logs/laravel.log

# Manually process specific date if needed
php artisan tinker --execute="
// This requires custom code modification to process specific dates
"
```
