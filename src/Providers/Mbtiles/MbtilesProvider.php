<?php

namespace TeamZac\LaravelTileserver\Providers\Mbtiles;

use Illuminate\Support\Facades\DB;
use TeamZac\LaravelTileserver\Contracts\TileProviderContract;
use TeamZac\LaravelTileserver\Tileserver;
use TeamZac\LaravelTileserver\TilesetMetadata;

class MbtilesProvider implements TileProviderContract
{
    /** @var SQLiteConnection */
    protected $database;

    /** @var string */
    protected $tileset;

    public function __construct($tileset)
    {
        $this->tileset = $tileset;

        DB::purge('tileset');
        
        config()->set('database.connections.tileset', array_merge(config('database.connections.sqlite'), [
            'database' => Tileserver::file($tileset),
        ]));

        $this->database = DB::connection('tileset');
    }
    
    public function getMetadata(): TilesetMetadata
    {
        return (new GenerateMetadataFromMbtiles)($this->tileset, $this->database);
    }

    public function getTile($x, $y, $z)
    {
        $flip = true;
        if ($flip) {
            $y = pow(2, $z) - 1 -$y;
        }

        $result = $this->database->table('tiles')
            // ->select('tile_data')
            ->where('zoom_level', (int) $z)
            ->where('tile_column', (int) $x)
            ->where('tile_row', (int) $y)
            ->first();

        return optional($result)->tile_data;
    }
}
