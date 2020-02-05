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
	 * Add a point column on the table
	 *
	 * @param string $column
	 * @param string $srid
	 *
	 * @return \Illuminate\Support\Fluent
	 */
	public function point($column, $srid = null)
	{
		return $this->addColumn('point', $column, ['srid' => $srid]);
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
