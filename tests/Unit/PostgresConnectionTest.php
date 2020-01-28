<?php

namespace Tests\Unit;

use LaravelSpatial\PostgresConnection;
use LaravelSpatial\Schema\PostgresBuilder;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Stubs\PDOStub;

/**
 * Class PostgresConnectionTest
 */
class PostgresConnectionTest extends TestCase
{
	/**
	 * @var \LaravelSpatial\PostgresConnection
	 */
    private $postgresConnection;

    protected function setUp(): void
    {
        $pgConfig = ['driver' => 'pgsql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->postgresConnection = new PostgresConnection(new PDOStub(), 'database', 'prefix', $pgConfig);
    }

    public function testGetSchemaBuilder(): void
	{
        $builder = $this->postgresConnection->getSchemaBuilder();

        $this->assertInstanceOf(PostgresBuilder::class, $builder);
    }

	public function testRegistersTypes(): void
	{
		$platform = $this->postgresConnection->getDoctrineSchemaManager()->getDatabasePlatform();

		$this->assertTrue($platform->hasDoctrineTypeMappingFor('geography'),'Should have mapping for geography.');
		$this->assertEquals($platform->getDoctrineTypeMapping('geography'),'string');
	}
}
