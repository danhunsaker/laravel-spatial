<?php

namespace LaravelSpatial\Schema;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

/**
 * Class Blueprint
 * @package LaravelSpatial\Schema
 */
class Blueprint extends BaseBlueprint
{
    /**
     * Create a new point column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function point($column)
    {
        return $this->addColumn('point', $column, ['srid' => null]);
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function enablePostgis()
    {
        return $this->addCommand('enablePostgis');
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function disablePostgis()
    {
        return $this->addCommand('disablePostgis');
    }
}
