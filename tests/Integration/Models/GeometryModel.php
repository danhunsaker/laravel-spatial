<?php

use LaravelSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'test_geometries';

    protected $spatialFields = [
        'location',
        'line',
        'shape',
        'geo',
        'multi_locations',
        'multi_lines',
        'multi_shapes',
        'multi_geometries',
    ];
}
