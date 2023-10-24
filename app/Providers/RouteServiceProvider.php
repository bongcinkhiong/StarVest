<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const Investor = '/investor/dashboard';
    public const Startup = '/startup/dashboard';
    public const HOME = '/admin/dashboard';
    public const LOGIN = '/auth/login';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    protected $namespace = 'App\Http\Controllers';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['auth:sanctum','api'])
                ->prefix('api')
                ->namespace($this->namespace.'\api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web','auth','CekRole:startup')
                ->namespace($this->namespace)
                ->group(base_path('routes/startup.php'));

            Route::middleware('web','auth','CekRole:admin')
                ->namespace($this->namespace.'\admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web','auth','CekRole:investor')
                ->namespace($this->namespace)
                ->group(base_path('routes/investor.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/auth.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
