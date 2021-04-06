<?php

namespace TeamZac\LaravelTileserver\Providers\Mbtiles;

use Illuminate\Support\Str;
use TeamZac\LaravelTileserver\TilesetMetadata;

class GenerateMetadataFromMbtiles
{
    protected $metadata;

    protected $tileset;

    protected $database;

    public function __invoke($tileset, $database)
    {
        $this->tileset = $tileset;
        $this->database = $database;

        $this->metadata = new TilesetMetadata;

        $database->table('metadata')->get()
            ->each(function($row) {
                $this->metadata->set($row->name, $row->value);
            });

        $this->autodetectMinAndMaxZoom()
            ->autodetectImageFormat()
            ->autodetectBounds()
            ->autodetectProfile()
            ->autodetectScale()
            ->autodetectScheme()
            ->autodetectTiles();

        return $this->metadata;
    }

    protected function autodetectMinAndMaxZoom()
    {
        if (! is_null($this->metadata->minzoom) && ! is_null($this->metadata->maxzoom)) {
            return $this;
        }

        $zooms = $this->database->table('tiles')
            ->select(
                \DB::raw('min(zoom_level) as minzoom, max(zoom_level) as maxzoom')
            )
            ->first();

        foreach ($zooms as $key => $value) {
            $this->metadata->set($key, $value);
        }

        return $this;
    }

    protected function autodetectImageFormat()
    {
        if (! is_null($this->metadata->format)) {
            return $this;
        }

        $result = $this->database->table('tiles')
            ->select(
                \DB::raw('hex(substr(tile_data, 1, 2)) as magic')
            )
            ->first();

        $this->metadata->set('format', $result->magic === 'FFD8' ? 'jpg' : 'png');

        return $this;
    }

    protected function autodetectBounds()
    {
        if (! is_null($this->metadata->bounds)) {
            return $this;
        }

        $result = $this->database->table('tiles')
            ->select(
                \DB::raw('min(tile_column) as w, max(tile_column) as e, min(tile_row) as s, max(tile_row) as n')
            )
            ->where('zoom_level', $this->maxzoom)
            ->first();

        $w = -180 + 360 * ($result->w / pow(2, $this->maxzoom));
        $e = -180 + 360 * ((1 + $result->e) / pow(2, $this->maxzoom));
        $n = $this->rowToLatitude($result->n, $this->maxzoom);
        $s = $this->rowToLatitude($result->s, $this->maxzoom);
        $this->metadata->set('bounds', collect([$w, $s, $e, $n])->toArray());
        return $this;
    }

    protected function rowToLatitude($row, $zoom)
    {
        $y = $row / pow(2, $zoom - 1) - 1;
        return rad2deg(2.0 * atan(exp(3.191459196 * $y)) - 1.57079632679489661922);
    }

    protected function autodetectProfile()
    {
        if (! is_null($this->metadata->profile)) {
            return $this;
        }

        $this->metadata->set('profile', 'mercator');
        return $this;
    }

    protected function autodetectScale()
    {
        if (! is_null($this->metadata->scale)) {
            return $this;
        }

        $this->metadata->set('scale', 1);
        return $this;
    }

    protected function autodetectScheme()
    {
        $this->metadata->set('scheme', 'xyz')
            ->set('tilejson', '2.0');

        return $this;
    }

    protected function autodetectTiles()
    {
        $this->metadata->set('tiles', [
            sprintf(
                '%s/%s/%s/{z}/{x}/{y}.pbf', 
                config('app.url'), 
                trim(config('tileserver.route_prefix'), '/'),
                $this->tileset
            )
        ]);
    }
}
