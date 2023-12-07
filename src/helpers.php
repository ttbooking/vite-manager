<?php

if (! function_exists('vite_app')) {
    /**
     * Generate Vite tags for an app.
     *
     * @param  string|null  $app
     * @param  array  ...$entryPoints
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    function vite_app($app = null, ...$entryPoints)
    {
        return new Illuminate\Support\HtmlString(
            app('vite')->app($app)->withEntryPoints($entryPoints, true)->toHtml()
        );
    }
}
