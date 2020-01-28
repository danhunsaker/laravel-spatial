<?php

namespace Tests\Unit\Schema\Grammars;

use Illuminate\Database\Connection;
use LaravelSpatial\MysqlConnection;
use LaravelSpatial\Schema\Blueprint;
use LaravelSpatial\Schema\Grammars\MySqlGrammar;
use Mockery;
use Tests\Unit\BaseTestCase;

/**
 * Class MySqlGrammarBaseTest
 *
 * @package Tests\Unit\Schema\Grammars
 */
class MySqlGrammarTest extends BaseTestCase
{
    public function testAddingGeometry(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->geometry('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOMETRY', $statements[0]);
    }

    public function testAddingPoint(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('POINT', $statements[0]);
    }

    public function testAddingLinestring(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->lineString('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('LINESTRING', $statements[0]);
    }

    public function testAddingPolygon(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('POLYGON', $statements[0]);
    }

    public function testAddingMultipoint(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiPoint('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('MULTIPOINT', $statements[0]);
    }

    public function testAddingMultiLinestring(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiLineString('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('MULTILINESTRING', $statements[0]);
    }

    public function testAddingMultiPolygon(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiPolygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('MULTIPOLYGON', $statements[0]);
    }

    public function testAddingGeometryCollection(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->geometryCollection('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOMETRYCOLLECTION', $statements[0]);
    }

    public function testAddRemoveSpatialIndex(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $blueprint->spatialIndex('foo');
        $addStatements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(2, $addStatements);
        $this->assertStringContainsStringIgnoringCase('alter table `test` add spatial index `test_foo_spatialindex`(`foo`)', $addStatements[1]);

        $blueprint->dropSpatialIndex(['foo']);
        $blueprint->dropSpatialIndex('test_foo_spatialindex');
        $dropStatements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $expectedSql = 'alter table `test` drop index `test_foo_spatialindex`';
        $this->assertCount(5, $dropStatements);
        $this->assertStringContainsStringIgnoringCase($expectedSql, $dropStatements[3]);
        $this->assertStringContainsStringIgnoringCase($expectedSql, $dropStatements[4]);
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
	{
        return Mockery::mock(MysqlConnection::class);
    }

	/**
	 * @return \LaravelSpatial\Schema\Grammars\MySqlGrammar
	 */
    protected function getGrammar(): MySqlGrammar
	{
        return new MySqlGrammar();
    }
}
