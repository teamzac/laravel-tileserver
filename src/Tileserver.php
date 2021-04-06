<?php

namespace TeamZac\LaravelTileserver;

class Tileserver
{
    public static function directory()
    {
        return sprintf(
            '%s/%s', rtrim(base_path(), '/'), trim(config('tileserver.directory'), '/')
        );
    }

    public static function file($file) 
    {
        return sprintf(
            '%s/%s.mbtiles', static::directory(), trim($file, '/')
        );
    }
}
