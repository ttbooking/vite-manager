<?php

namespace TTBooking\ViteManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getDefaultDriver()
 * @method static \TTBooking\ViteManager\ViteManager useAppFactory(callable $appFactory)
 * @method static \TTBooking\ViteManager\Contracts\Vite app(string|null $app = null)
 * @method static \TTBooking\ViteManager\Contracts\Vite configure(array $config)
 * @method static array preloadedAssets()
 * @method static string|null cspNonce()
 * @method static string useCspNonce(string|null $nonce = null)
 * @method static \TTBooking\ViteManager\Contracts\Vite useIntegrityKey(string|false $key)
 * @method static \TTBooking\ViteManager\Contracts\Vite withEntryPoints(array $entryPoints)
 * @method static \TTBooking\ViteManager\Contracts\Vite useManifestFilename(string $filename)
 * @method static string hotFile()
 * @method static \TTBooking\ViteManager\Contracts\Vite useHotFile(string $path)
 * @method static \TTBooking\ViteManager\Contracts\Vite useBuildDirectory(string $path)
 * @method static \TTBooking\ViteManager\Contracts\Vite useScriptTagAttributes(callable|array $attributes)
 * @method static \TTBooking\ViteManager\Contracts\Vite useStyleTagAttributes(callable|array $attributes)
 * @method static \TTBooking\ViteManager\Contracts\Vite usePreloadTagAttributes(callable|array|false $attributes)
 * @method static \Illuminate\Support\HtmlString|void reactRefresh()
 * @method static string asset(string $asset, string|null $buildDirectory = null)
 * @method static string content(string $asset, string|null $buildDirectory = null)
 * @method static string|null manifestHash(string|null $buildDirectory = null)
 * @method static bool isRunningHot()
 * @method static string toHtml()
 * @method static mixed driver(string|null $driver = null)
 * @method static \TTBooking\ViteManager\ViteManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \TTBooking\ViteManager\ViteManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \TTBooking\ViteManager\ViteManager forgetDrivers()
 *
 * @see \TTBooking\ViteManager\ViteManager
 */
class Vite extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'vite';
    }
}
