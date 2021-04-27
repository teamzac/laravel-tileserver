<?php

namespace TeamZac\LaravelTileserver\Contracts;

interface TileProviderContract
{
    public function getMetadata();

    /**
     * Find the tile given the x, y, and z
     *
     * @param float $x
     * @param float $y
     * @param float $z
     * @return blob 
     * @throws TileNotFoundException
     */
    public function getTile($x, $y, $z);
}
