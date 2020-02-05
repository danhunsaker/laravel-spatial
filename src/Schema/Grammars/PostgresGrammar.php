<?php

namespace LaravelSpatial\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\PostgresGrammar as BasePostgresGrammar;
use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class PostgresGrammar
 *
 * @package LaravelSpatial\Schema\Grammars
 */
class PostgresGrammar extends BasePostgresGrammar
{
    /**
     * @inheritDoc
     */
    public function typePoint(Fluent $column)
    {
        return parent::typePoint($column);
    }

    /**
     * @inheritDoc
     */
    public function typeMultiPoint(Fluent $column)
    {
        return parent::typeMultiPoint($column);
    }

    /**
     * @inheritDoc
     */
    public function typePolygon(Fluent $column)
    {
        return parent::typePolygon($column);
    }

    /**
     * @inheritDoc
     */
    public function typeMultiPolygon(Fluent $column)
    {
        return parent::typeMultiPolygon($column);
    }

    /**
     * @inheritDoc
     */
    public function typeLineString(Fluent $column)
    {
        return parent::typeLineString($column);
    }

    /**
     * @inheritDoc
     */
    public function typeMultiLineString(Fluent $column)
    {
        return parent::typeMultiLineString($column);
    }

    /**
     * @inheritDoc
     */
    public function typeGeography(Fluent $column)
    {
        return 'GEOGRAPHY';
    }

    /**
     * Adds a statement to add a geometry geometry column
     *
     * @param Fluent $column
     * @return string
     */
    public function typeGeometry(Fluent $column)
    {
        return 'GEOGRAPHY(GEOMETRY, 4326)';
    }

    /**
     * @inheritDoc
     */
    public function typeGeometryCollection(Fluent $column)
    {
        return parent::typeGeometryCollection($column);
    }

    /**
     * Adds a statement to create the postgis extension
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     *
     * @return string
     */
    public function compileEnablePostgis(Blueprint $blueprint, Fluent $command)
    {
        return 'CREATE EXTENSION postgis';
    }

    /**
     * Adds a statement to drop the postgis extension
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    public function compileDisablePostgis(Blueprint $blueprint, Fluent $command)
    {
        return 'DROP EXTENSION postgis';
    }
}
