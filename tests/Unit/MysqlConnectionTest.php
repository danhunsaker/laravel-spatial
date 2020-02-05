<?php

namespace Tests\Unit;

use LaravelSpatial\MysqlConnection;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Stubs\PDOStub;

/**
 * Class MysqlConnectionTest
 */
class MysqlConnectionTest extends TestCase
{
    /**
     * @var \LaravelSpatial\MysqlConnection
     */
    private $mysqlConnection;

    protected function setUp(): void
    {
        $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];
        $this->mysqlConnection = new MysqlConnection(new PDOStub(), 'database', 'prefix', $mysqlConfig);
    }

    public function testRegistersTypes(): void
    {
        $types = [
            'geometry',
            'point',
            'linestring',
            'polygon',
            'multipoint',
            'multilinestring',
            'multipolygon',
            'geomcollection',
            'geometrycollection',
        ];

        $platform = $this->mysqlConnection->getDoctrineSchemaManager()->getDatabasePlatform();

        foreach ($types as $type) {
            $this->assertTrue($platform->hasDoctrineTypeMappingFor($type), sprintf('Platform should have mapping for %s.', $type));
            $this->assertEquals('string', $platform->getDoctrineTypeMapping($type));
        }
    }
}
