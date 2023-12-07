<?php

namespace TTBooking\ViteManager;

use Illuminate\Support\Facades\Blade;
use TTBooking\ViteManager\Contracts\Vite;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('viteApp', function ($arguments) {
            [$app, $entryPoints] = array_map('trim', explode(',', $arguments, 2)) + [1 => null];

            return $entryPoints
                ? "<?php echo app('vite')->app($app)->withEntryPoints([$entryPoints])->toHtml(); ?>"
                : "<?php echo app('vite')->app($app)->toHtml(); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/vite.php' => $this->app->configPath('vite.php'),
            ], ['vite-manager-config', 'vite-manager', 'config']);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vite.php', 'vite');

        $this->app->singleton('vite', fn ($app) => new ViteManager($app));
        $this->app->singleton('vite.app', fn ($app) => $app['vite']->app());
        $this->app->bind(Vite::class, 'vite.app');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['vite', 'vite.app', Vite::class];
    }
}
