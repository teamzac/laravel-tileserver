<?php

namespace TeamZac\LaravelTileserver\Contracts;

interface TileProviderContract
{
    public function getMetadata();

    public function getTile($x, $y, $z);
}
