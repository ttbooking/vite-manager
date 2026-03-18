<?php

namespace TTBooking\ViteManager;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use TTBooking\ViteManager\Contracts\Vite as ViteContract;

class ViteManager extends Manager implements ViteContract
{
    /**
     * The registered app factory.
     *
     * @var (callable(ViteContract, string, array, Container): ViteContract)|null
     */
    protected $appFactory = null;

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('vite.app', 'default');
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return ViteContract
     */
    protected function createDriver($driver)
    {
        try {
            return parent::createDriver($driver);
        } catch (InvalidArgumentException) {
            return $this->createApp($driver);
        }
    }

    /**
     * Create a new app instance.
     *
     * @param  string  $app
     * @return ViteContract
     */
    protected function createApp($app)
    {
        return ($this->appFactory ?? function (ViteContract $vite, string $app, array $config) {
            return $vite->configure($config);
        })(new Vite, $app, config("vite.apps.$app", []), $this->container);
    }

    /**
     * Register an app factory callback.
     *
     * @param  callable(ViteContract, string, array, Container): ViteContract  $appFactory
     * @return $this
     */
    public function useAppFactory($appFactory)
    {
        $this->appFactory = $appFactory;

        return $this;
    }

    /**
     * Get an app instance.
     *
     * @param  string|null  $app
     * @return ViteContract
     *
     * @throws InvalidArgumentException
     */
    public function app($app = null)
    {
        return $this->driver($app);
    }

    /**
     * Apply configuration to the Vite instance.
     *
     * @param  array  $config
     * @return ViteContract
     */
    public function configure($config)
    {
        return $this->app()->configure($config);
    }

    /**
     * Get the preloaded assets.
     *
     * @return array
     */
    public function preloadedAssets()
    {
        return $this->app()->preloadedAssets();
    }

    /**
     * Get the Content Security Policy nonce applied to all generated tags.
     *
     * @return string|null
     */
    public function cspNonce()
    {
        return $this->app()->cspNonce();
    }

    /**
     * Generate or set a Content Security Policy nonce to apply to all generated tags.
     *
     * @param  string|null  $nonce
     * @return string
     */
    public function useCspNonce($nonce = null)
    {
        return $this->app()->useCspNonce($nonce);
    }

    /**
     * Use the given key to detect integrity hashes in the manifest.
     *
     * @param  string|false  $key
     * @return ViteContract
     */
    public function useIntegrityKey($key)
    {
        return $this->app()->useIntegrityKey($key);
    }

    /**
     * Set the Vite entry points.
     *
     * @param  array  $entryPoints
     * @param  bool  $append
     * @return ViteContract
     */
    public function withEntryPoints($entryPoints, $append = false)
    {
        return $this->app()->withEntryPoints($entryPoints, $append);
    }

    /**
     * Set the filename for the manifest file.
     *
     * @param  string  $filename
     * @return ViteContract
     */
    public function useManifestFilename($filename)
    {
        return $this->app()->useManifestFilename($filename);
    }

    /**
     * Get the Vite "hot" file path.
     *
     * @return string
     */
    public function hotFile()
    {
        return $this->app()->hotFile();
    }

    /**
     * Set the Vite "hot" file path.
     *
     * @param  string  $path
     * @return ViteContract
     */
    public function useHotFile($path)
    {
        return $this->app()->useHotFile($path);
    }

    /**
     * Set the Vite build directory.
     *
     * @param  string  $path
     * @return ViteContract
     */
    public function useBuildDirectory($path)
    {
        return $this->app()->useBuildDirectory($path);
    }

    /**
     * Use the given callback to resolve attributes for script tags.
     *
     * @param  (callable(string, string, ?array, ?array): array)|array  $attributes
     * @return ViteContract
     */
    public function useScriptTagAttributes($attributes)
    {
        return $this->app()->useScriptTagAttributes($attributes);
    }

    /**
     * Use the given callback to resolve attributes for style tags.
     *
     * @param  (callable(string, string, ?array, ?array): array)|array  $attributes
     * @return ViteContract
     */
    public function useStyleTagAttributes($attributes)
    {
        return $this->app()->useStyleTagAttributes($attributes);
    }

    /**
     * Use the given callback to resolve attributes for preload tags.
     *
     * @param  (callable(string, string, ?array, ?array): (array|false))|array|false  $attributes
     * @return ViteContract
     */
    public function usePreloadTagAttributes($attributes)
    {
        return $this->app()->usePreloadTagAttributes($attributes);
    }

    /**
     * Generate Vite tags for an entrypoint.
     *
     * @param  string|string[]  $entrypoints
     * @param  string|null  $buildDirectory
     * @return HtmlString
     *
     * @throws \Exception
     */
    public function __invoke($entrypoints, $buildDirectory = null)
    {
        return $this->app()->__invoke($entrypoints, $buildDirectory);
    }

    /**
     * Generate React refresh runtime script.
     *
     * @return HtmlString|void
     */
    public function reactRefresh()
    {
        return $this->app()->reactRefresh();
    }

    /**
     * Get the URL for an asset.
     *
     * @param  string  $asset
     * @param  string|null  $buildDirectory
     * @return string
     */
    public function asset($asset, $buildDirectory = null)
    {
        return $this->app()->asset($asset, $buildDirectory);
    }

    /**
     * Get the content of a given asset.
     *
     * @param  string  $asset
     * @param  string|null  $buildDirectory
     * @return string
     *
     * @throws \Exception
     */
    public function content($asset, $buildDirectory = null)
    {
        return $this->app()->content($asset, $buildDirectory);
    }

    /**
     * Get a unique hash representing the current manifest, or null if there is no manifest.
     *
     * @param  string|null  $buildDirectory
     * @return string|null
     */
    public function manifestHash($buildDirectory = null)
    {
        return $this->app()->manifestHash($buildDirectory);
    }

    /**
     * Determine if the HMR server is running.
     *
     * @return bool
     */
    public function isRunningHot()
    {
        return $this->app()->isRunningHot();
    }

    /**
     * Clean up orphaned Vite assets.
     *
     * @param  string|null  $buildDirectory
     * @return void
     */
    public function prune($buildDirectory = null)
    {
        $this->app()->prune($buildDirectory);
    }

    /**
     * Get the Vite tag content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->app()->toHtml();
    }
}
