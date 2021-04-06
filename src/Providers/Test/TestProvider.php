<?php

namespace TeamZac\LaravelTileserver\Providers\Test;

use TeamZac\LaravelTileserver\Contracts\TileProviderContract;

class TestProvider implements TileProviderContract
{
    public function getMetadata()
    {

    }

    public function getTile($x, $y, $z)
    {
        
    }
}
