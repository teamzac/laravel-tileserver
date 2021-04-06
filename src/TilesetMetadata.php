<?php

namespace TeamZac\LaravelTileserver;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TeamZac\LaravelTileserver\Exceptions\MetadataValidationException;

class TilesetMetadata
{
    protected $attributes = [];

    protected $rules = [
        'name' => 'required|string',
        'description' => 'string',
        'version' => 'string',
        'minzoom' => 'required|integer',
        'maxzoom' => 'required|integer',
        'center' => 'required|string',
        'bounds' => 'required|array',
        'type' => 'string',
        'format' => 'required|in:pbf',
        'generator' => 'sometimes',
        'generator_options' => 'sometimes',
        'basename' => 'sometimes',
        'profile' => 'required',
        'scale' => 'required|integer',
        'tiles' => 'required|array',
        'tilejson' => 'required|string',
        'scheme' => 'required|in:xyz',
        'vector_layers' => 'sometimes|array',
        'tilestats' => 'sometimes'
    ];

    public function all()
    {
        try {
            return $this->validate();
        } catch (\Exception $e) {
            dd($e->errors(), $this->attributes);
        }

        return $this->attributes;
    }

    public function validate()
    {
        return resolve(\Illuminate\Contracts\Validation\Factory::class)
            ->make($this->attributes, $this->rules)
            ->validate();
    }

    public function set($key, $value)
    {
        $value = $this->callCustomValidator($key, $value);

        $this->attributes[$key] = $value;
        return $this;
    }

    protected function callCustomValidator($key, $value)
    {
        $customMethod = sprintf('validate%s', (string) Str::of($key)->camel()->ucfirst());
        if (method_exists($this, $customMethod)) {
            return $this->{$customMethod}($value);
        }
        return $value;
    }

    protected function validateCenter($center)
    {
        $parts = explode(',', $center);
        if (count($parts) != 3) {
            throw MetadataValidationException::invalidCenter();
        }
        return $center;
    }

    protected function validateBounds($bounds)
    {
        if (is_string($bounds)) {
            $bounds = explode(',', $bounds);
        }

        // ensure the value is an array and has four points
        if (!is_array($bounds) && count($bounds) != 4) {
            throw MetadataValidationException::insufficientBounds();
        }

        // coerce each value to float
        return array_map(function($bound) {
            return floatval($bound);
        }, $bounds);
    }

    protected function validateJson($json)
    {
        return is_string($json) ?
            json_decode($json) :
            $json;
    }

    public function __get($key)
    {
        return Arr::get($this->attributes, $key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
}
