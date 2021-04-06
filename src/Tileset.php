<?php

namespace TeamZac\LaravelTileserver;

use TeamZac\LaravelTileserver\Providers\Mbtiles\MbtilesProvider;
use TeamZac\LaravelTileserver\Providers\Test\TestProvider;

class Tileset
{
    protected $tileProvider;

    protected static $defaultProvider = MbtilesProvider::class;

    public function __construct($tileProvider)
    {
        $this->tileProvider = $tileProvider;
    }

    public static function fromMbtiles($file)
    {
        return new static(new MbtilesProvider($file));
    }

    public static function test()
    {
        static::$defaultProvider = TestProvider::class;
    }

    public function getTile($x, $y, $z)
    {
        return $this->tileProvider->getTile($x, $y, $z);
    }

    public function getMetadata()
    {
        return $this->tileProvider->getMetadata();
    }
}
