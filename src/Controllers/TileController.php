<?php

namespace TeamZac\LaravelTileserver\Controllers;

use Illuminate\Http\Request;
use TeamZac\LaravelTileserver\Tileset;

class TileController
{
    public function __invoke(Request $request, $tileset, $z, $x, $y)
    {
        // handle non modified etags
        $tiles = Tileset::fromMbtiles($tileset);

        $tile = $tiles->getTile($x, $y, $z);
        if (is_null($tile)) {
            abort(404);
        }

        return response($tile)
            ->header('Content-Type', 'application/x-protobuf')
            ->header('Content-Encoding', 'gzip')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
