# Laravel Vite Manager

This package extends [Vite functionality built in Laravel](https://laravel.com/docs/10.x/vite), so you can manage
multiple entry point (hereafter _app_) configurations without clogging your Blade templates with clumsy configuration code.
It is especially useful if your project have multiple JS entry points, or if you develop a package which publishes its
own Vite managed assets.

## Installation

You can install this package using Composer:
```bash
composer require ttbooking/vite-manager
```

Then you'll probably need to publish configuration file:
```bash
artisan vendor:publish --tag=vite-manager
```

## Configuration

There are 3 ways to configure Vite "apps".

### Config file

The most basic way. Use it if you develop a project with few entry points.
Example `config/vite.php` file:
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Vite App
    |--------------------------------------------------------------------------
    */

    'app' => env('VITE_APP', 'default'),

    'apps' => [

        'default' => [
            'entry_points' => ['vite/legacy-polyfills-legacy', 'resources/js/app-legacy.js', 'resources/js/app.js'],
            'build_directory' => 'build',
        ],

        'myapp' => [
            'entry_points' => ['vite/legacy-polyfills-legacy', 'src/app-legacy.js', 'src/app.js'],
            'build_directory' => 'build/packages/myapp',
            'hot_file' => 'build/packages/myapp/hot',
        ],

    ],

];
```

You may find complete list of configurable options [here](tests/Unit/ViteManagerTest.php).

Note `VITE_APP` environment variable, which you can use to customize default entry point per environment:
```dotenv
# Your .env file
VITE_APP=custom
```

### Service provider's `boot` method

Advanced method. Use it if you develop a package with Vite assets, or if you need fine-grained control over your app
configuration:
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use TTBooking\ViteManager\Facades\Vite;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::withEntryPoints(['vite/legacy-polyfills-legacy', 'resources/js/app-legacy.js', 'resources/js/app.js'])
            ->useBuildDirectory('build');

        Vite::app('myapp')
            ->withEntryPoints(['vite/legacy-polyfills-legacy', 'src/app-legacy.js', 'src/app.js'])
            ->useBuildDirectory('build/packages/myapp')
            ->useHotFile(public_path('build/packages/myapp/hot'))
            ->useScriptTagAttributes(function (string $src) {
                return Str::contains($src, '-legacy') ? ['type' => 'text/javascript', 'nomodule'] : [];
            })
            ->usePreloadTagAttributes(function (string $src) {
                return Str::contains($src, '-legacy') ? false : [];
            });
    }
}
```

You can use your `AppServiceProvider.php` (or other of your choice) for this matter, but it is recommended to use
separate provider, especially if you have many entry point configurations. Just don't forget to register this service
provider in your `config/app.php` file.

### App factory

The most advanced method. Use it if you have many similar apps/workspaces to be configured alike:
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use TTBooking\ViteManager\Contracts\Vite as ViteContract;
use TTBooking\ViteManager\Facades\Vite;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::useAppFactory(fn (ViteContract $vite, string $app, array $config) => $vite
            ->withEntryPoints(['vite/legacy-polyfills-legacy', 'src/app-legacy.js', 'src/app.js'])
            ->useBuildDirectory("build/packages/$app")
            ->useHotFile(public_path("build/packages/$app/hot"))
            ->useScriptTagAttributes(function (string $src) {
                return Str::contains($src, '-legacy') ? ['type' => 'text/javascript', 'nomodule'] : [];
            })
            ->usePreloadTagAttributes(function (string $src) {
                return Str::contains($src, '-legacy') ? false : [];
            })
            ->configure($config)
        );
    }
}
```

Closure passed into `useAppFactory` retrieves 4 arguments: a `Vite` class instance to be configured by the factory,
app name, corresponding configuration section (if exists), and application's container instance.
You may (or may not) want to amend instance's configuration by the options from config file.
To do this, call `configure` method on `Vite` instance with the given `$config` variable.
This way, end user could change predefined options on per-app basis.

## Usage

`@viteApp` directive should be used in Blade templates:
```blade
{{-- Default entry point --}}
@viteApp

{{-- Example app entry point --}}
@viteApp('myapp')

{{-- Example app with additional entry point --}}
@viteApp('myapp', "src/Pages/{$page['component']}.vue")
```

If you are using template engine other than Blade, you can use `vite_app` helper function:
```twig
{# Default entry point #}
{{ vite_app() }}

{# Example app entry point #}
{{ vite_app('myapp') }}

{# Example app with additional entry point #}
vite_app('myapp', 'src/Pages/' ~ page.component ~ '.vue')
```

If you are using [TwigBridge](https://github.com/rcrowe/TwigBridge), don't forget to register this function in
`config/twigbridge.php` file under `extensions.functions` section.

