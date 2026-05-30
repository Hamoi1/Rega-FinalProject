<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiter();
        $this->configureCommands();
        $this->configureModels();
        $this->configureDates();
        $this->configureBladeDirectives();
    }

    /**
     * Configure the date classes provided by your application.
     */
    public function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the RateLimiter provided by your application.
     */
    private function configureRateLimiter(): void
    {
        RateLimiter::for('web', fn(Request $request) => app()->isProduction()
            ? Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
            : Limit::none());
        RateLimiter::for('api', fn(Request $request) => app()->isProduction()
            ? Limit::perMinute(500)->by($request->user()?->id ?: $request->ip())
            : Limit::none());
    }

    /**
     * Configure the Artisan commands provided by your application.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
    }

    /**
     * Configure the Models provided by your application.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
        Model::unguard();
    }

    /**
     * Configure the Blade directives provided by your application.
     */
    private function configureBladeDirectives(): void
    {
        Blade::directive('echo', fn(string $value): string => sprintf('<?= %s; ?>', $value), true);
    }
}
