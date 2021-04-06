# Add an XYZ tile server to your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teamzac/laravel-tileserver.svg?style=flat-square)](https://packagist.org/packages/teamzac/laravel-tileserver)

This is a small package that allows your Laravel application to provide an XYZ tile server using .mbtiles datasets as the source. It was inspired by [maptiler/tileserver-php](https://github.com/maptiler/tileserver-php), which was helpful for understanding the minimum requirements, but is kind of a mess and not something you'd want to drop into an existing project.

However, this package is built for our particular use case and is not as full-featured as the one linked above. It only handles .mbtiles files and metadata validation is less robust because we have more control over the creation of the source data. It might be expanded in the future if our needs change. But if you just want to serve your own mbtiles as a vector layer for Mapbox or something, feel free to give it a shot!

## More Info

You can use [Tippecanoe from Mapbox](https://github.com/mapbox/tippecanoe) to create your own mbtiles datasets from GeoJSON, CSV, and other file formats.

Various mapping libraries allow you to add custom layers. Consult the appropriate documentation to learn how each one handles them.

## Installation

You can install the package via composer:

```bash
composer require teamzac/laravel-tileserver
```

It requires Laravel 8+.

## Usage

The package is pretty simple to use. The service provider is auto-discovered. You can publish the config file or simply override the three settings (see below) with environment variables. 

Then, simply register the routes in a routes file or RouteServiceProvider:

``` php
Route::tileserver();
```

If you'd like to wrap certain middlware around the routes, such as authentication middleware, you can do so:

```php
Route::middleware('auth')->group(function() {
    Route::tileserver();
});
```

This will expose two routes to your app (or three, if you choose):

### /tileserver/{tileset}/{z}/{x}/{y}.pbf

This is the tile template you can use in your custom map layers.

### /tileserver/{tileset}.json

This is a JSON endpoint that returns the metadata for all available tilesets. You can choose whether to hide this route in the config file.

### /tileserver/tilesets.json

You can also expose this route to provide a JSON endpoint showing all available tilesets. By default, this is turned off. See the Configuration section for more details.

## Configuration

Three configuration settings are provided:

1. Where your .mbtiles data files are stored
2. What route prefix to use for your endpoints
3. Whether to expose the JSON endpoint listing all available tilesets

You can set all of these via environment variables, or publish the config file and manage them there.



### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email chad@zactax.com instead of using the issue tracker.

## Credits

- [Chad Janicek](https://github.com/teamzac)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).