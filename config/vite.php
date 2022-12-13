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
            'entry_points' => ['resources/js/app.js'],
            'build_directory' => 'build',
        ],

    ],

];
