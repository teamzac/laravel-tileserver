<?php

namespace TeamZac\LaravelTileserver;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TeamZac\LaravelTileserver\Controllers\MetadataController;
use TeamZac\LaravelTileserver\Controllers\TileController;
use TeamZac\LaravelTileserver\Controllers\TilesetsController;

class LaravelTileserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('tileserver.php'),
            ], 'config');
        }

        Route::macro('tileserver', function() {
            $options = [
                'prefix' => config('tileserver.route_prefix'),
                'as' => 'tileserver.',
            ];
            Route::group($options, function($router) {
                if (config('tileserver.routes.tilesets') === true) {
                    $router->get('/tilesets.json', TilesetsController::class)->name('tilesets');
                }
                
                $router->get('/{tileset}/{z}/{x}/{y}.pbf', TileController::class)->name('tile');
                $router->get('/{tileset}.json', MetadataController::class)->name('metadata');
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'tileserver');

        // Register the main class to use with the facade
        $this->app->singleton(TilesetManager::class, function ($app) {
            return new TilesetManager($app);
        });
    }
}
