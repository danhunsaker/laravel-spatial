<?php

namespace LaravelSpatial\Schema;

use Closure;
use Illuminate\Database\Schema\PostgresBuilder as BasePostgresBuilder;

/**
 * Class PostgresBuilder
 *
 * @package LaravelSpatial\Schema
 */
class PostgresBuilder extends BasePostgresBuilder
{

    /**
     * @inheritDoc
     */
    protected function createBlueprint($table, Closure $callback = null)
    {
        return new Blueprint($table, $callback);
    }
}
