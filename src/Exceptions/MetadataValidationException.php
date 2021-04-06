<?php

namespace TeamZac\LaravelTileserver\Exceptions;

class MetadataValidationException extends \Exception
{
    public static function insufficientBounds()
    {
        return new static('Invalid value for `bounds`: array must contain 4 values');
    }

    public static function invalidCenter()
    {
        return new static('Invalid value for `center`: must be of format lng,lat,zoom');
    }
}
