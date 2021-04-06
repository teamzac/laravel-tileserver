<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    // Path to the directory where your mbtiles are located
    'directory' => env('TILESERVER_STORAGE_PATH', '/storage/tiles'),

    // The url prefix for your route maps
    'route_prefix' => env('TILESERVER_ROUTE_PREFIX', '/tileserver/'),

    // Choose whether to expose a route that shows all available tilesets
    'routes' => [
        'tilesets' => env('TILESERVER_EXPOSE_TILESETS_ROUTE', false),
    ]
];
