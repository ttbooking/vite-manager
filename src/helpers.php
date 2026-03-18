<?php

use Illuminate\Support\HtmlString;

if (! function_exists('vite_app')) {
    /**
     * Generate Vite tags for an app.
     *
     * @param  string|null  $app
     * @param  array  ...$entryPoints
     * @return HtmlString
     *
     * @throws Exception
     */
    function vite_app($app = null, ...$entryPoints)
    {
        return new HtmlString(
            app('vite')->app($app)->withEntryPoints($entryPoints, true)->toHtml()
        );
    }
}
