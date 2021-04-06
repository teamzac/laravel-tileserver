<?php

namespace TeamZac\LaravelTileserver\Controllers;

use Illuminate\Http\Request;
use TeamZac\LaravelTileserver\Tileserver;
use TeamZac\LaravelTileserver\Tileset;

class TilesetsController
{
    public function __invoke(Request $request)
    {
        $tilesets = $this->getMbtiles()
            ->map(function($mbtile) {
                return [
                    'tileset' => $mbtile, 
                    'metadata' => Tileset::fromMbtiles($mbtile)->getMetadata()->all()
                ];
            });

        return $tilesets;
    }

    protected function getMbtiles()
    {
        $files = glob(Tileserver::directory().'/*.mbtiles');

        return collect($files)->map(function($file) {
            return collect(explode('/', $file))->last();
        })->map(function($file) {
            return str_replace('.mbtiles', '', $file);
        });
    }
}
