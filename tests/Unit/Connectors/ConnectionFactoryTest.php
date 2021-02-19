<?php

namespace Tests\Unit\Connectors;

use Illuminate\Container\Container;
use Illuminate\Database\SQLiteConnection;
use LaravelSpatial\MysqlConnection;
use LaravelSpatial\PostgresConnection;
use Mockery;
use Tests\Unit\BaseTestCase;
use Tests\Unit\Stubs\ConnectionFactoryStub;
use Tests\Unit\Stubs\PDOStub;

/**
 * Class ConnectionFactoryBaseTest
 *
 * @package Tests\Unit\Connectors
 * @coversDefaultClass \LaravelSpatial\Connectors\ConnectionFactory
 */
class ConnectionFactoryTest extends BaseTestCase
{
    public function testMysqlMakeCallsCreateConnection(): void
    {
        $pdo     = new PDOStub();
        $factory = new ConnectionFactoryStub(new Container());
        $conn    = $factory->createConnection('mysql', $pdo, 'database');

        $this->assertInstanceOf(MysqlConnection::class, $conn);
    }

    public function testPostgresMakeCallsCreateConnection(): void
    {
	    $pdo     = new PDOStub();
	    $factory = new ConnectionFactoryStub(new Container());
        $conn = $factory->createConnection('pgsql', $pdo, 'database');

        $this->assertInstanceOf(PostgresConnection::class, $conn);
    }

    public function testCreateConnectionDifferentDriver(): void
    {
	    $pdo     = new PDOStub();
	    $factory = new ConnectionFactoryStub(new Container());
        $conn    = $factory->createConnection('sqlite', $pdo, 'database');

        $this->assertInstanceOf(SQLiteConnection::class, $conn);
    }
}
