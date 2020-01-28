<?php

namespace Tests\Unit\Schema\Grammars;

use Illuminate\Database\Connection;
use LaravelSpatial\PostgresConnection;
use LaravelSpatial\Schema\Blueprint;
use LaravelSpatial\Schema\Grammars\PostgresGrammar;
use Mockery;
use Tests\Unit\BaseTestCase;

/**
 * Class PostgresGrammarTest
 *
 * @package Tests\Unit\Schema\Grammars
 */
class PostgresGrammarTest extends BaseTestCase
{
    public function testAddingPoint(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->point('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(POINT, 4326)', $statements[0]);
    }

    public function testAddingLinestring(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->lineString('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(LINESTRING, 4326)', $statements[0]);
    }

    public function testAddingPolygon(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(POLYGON, 4326)', $statements[0]);
    }

    public function testAddingMultipoint(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiPoint('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(MULTIPOINT, 4326)', $statements[0]);
    }

    public function testAddingMultiLinestring(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiLineString('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(MULTILINESTRING, 4326)', $statements[0]);
    }

    public function testAddingMultiPolygon(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->multiPolygon('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOGRAPHY(MULTIPOLYGON, 4326)', $statements[0]);
    }

    public function testAddingGeometry(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->geometry('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOMETRY', strtoupper($statements[0]));
    }

    public function testAddingGeometryCollection(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->geometryCollection('foo');
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('GEOMETRYCOLLECTION', strtoupper($statements[0]));
    }

    public function testEnablePostgis(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->enablePostgis();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('CREATE EXTENSION postgis', $statements[0]);
    }

    public function testDisablePostgis(): void
	{
        $blueprint = new Blueprint('test');
        $blueprint->disablePostgis();
        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertStringContainsStringIgnoringCase('DROP EXTENSION postgis', $statements[0]);
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
	{
        return Mockery::mock(PostgresConnection::class);
    }

	/**
	 * @return \LaravelSpatial\Schema\Grammars\PostgresGrammar
	 */
    protected function getGrammar(): PostgresGrammar
	{
        return new PostgresGrammar();
    }
}
