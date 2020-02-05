<?php

namespace LaravelSpatial\Eloquent;

use GeoJson\Geometry\Geometry;
use geoPHP\geoPHP;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use LaravelSpatial\Exceptions\SpatialParseException;

/**
 * Class Builder
 *
 * @package LaravelSpatial\Eloquent
 */
class Builder extends EloquentBuilder
{
    /**
     * @inheritDoc
     */
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof Geometry) {
                try {
                    $wkt = geoPHP::load(json_decode(json_encode($value->jsonSerialize()), false), 'json')
                                 ->out('wkt');
                } catch (\Exception $e) {
                    throw new SpatialParseException(
                        \sprintf('Unable to parse geometry data for column %s.', $key),
                        0,
                        $e
                    );
                }

                $value = $this->getQuery()->raw("ST_GeomFromText('{$wkt}')");
            }
        }

        return parent::update($values);
    }
}
