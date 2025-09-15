<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom rate limiters
        RateLimiter::for('investments', function (Request $request) {
            $userId = optional($request->user())->id ?: $request->ip();
            return \Illuminate\Cache\RateLimiting\Limit::perMinutes(1, 5)->by('investments|'.$userId);
        });

        RateLimiter::for('deposits', function (Request $request) {
            $userId = optional($request->user())->id ?: $request->ip();
            return \Illuminate\Cache\RateLimiting\Limit::perMinutes(1, 8)->by('deposits|'.$userId);
        });

        RateLimiter::for('withdrawals', function (Request $request) {
            $userId = optional($request->user())->id ?: $request->ip();
            return \Illuminate\Cache\RateLimiting\Limit::perMinutes(1, 5)->by('withdrawals|'.$userId);
        });

        // Blade directive for formatting Money objects
        \Illuminate\Support\Facades\Blade::directive('money', function ($expression) {
            return "<?php echo number_format(({$expression}) instanceof \\App\\Support\\Money ? ({$expression})->toFloat() : (float)({$expression}), 2); ?>";
        });
    }
}
