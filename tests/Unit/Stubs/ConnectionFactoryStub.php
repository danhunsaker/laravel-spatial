<?php

namespace Tests\Unit\Stubs;

use LaravelSpatial\Connectors\ConnectionFactory;

/**
 * Class ConnectionFactoryStub
 */
class ConnectionFactoryStub extends ConnectionFactory
{
    public function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
