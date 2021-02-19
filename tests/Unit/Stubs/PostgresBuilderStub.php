<?php

namespace Tests\Unit\Stubs;

use Closure;
use LaravelSpatial\Schema\PostgresBuilder;

/**
 * Class PostgresBuilderStub
 */
class PostgresBuilderStub extends PostgresBuilder
{
    /**
     * @inheritDoc
     */
    public function createBlueprint($table, Closure $callback = null)
    {
        return parent::createBlueprint($table, $callback);
    }
}
