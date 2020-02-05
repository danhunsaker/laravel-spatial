<?php

namespace Tests\Unit\Schema;

use LaravelSpatial\Schema\Blueprint;
use Mockery;
use Tests\Unit\BaseTestCase;

/**
 * Class BlueprintTest
 *
 * @package Tests\Unit\Schema
 */
class BlueprintTest extends BaseTestCase
{
    /**
     * @var \Mockery\Mock|Blueprint
     */
    protected $blueprint;

    public function setUp(): void
    {
        parent::setUp();

        $this->blueprint = Mockery::mock(Blueprint::class)
            ->makePartial()->shouldAllowMockingProtectedMethods();
    }

    public function testGeometry(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometry', 'col')
            ->once();

        $this->blueprint->geometry('col');
    }

    public function testPoint(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('point', 'col', ['srid' => null])
            ->once();

        $this->blueprint->point('col');
    }

    public function testLinestring(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('linestring', 'col')
            ->once();

        $this->blueprint->lineString('col');
    }

    public function testPolygon(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('polygon', 'col')
            ->once();

        $this->blueprint->polygon('col');
    }

    public function testMultiPoint(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipoint', 'col')
            ->once();

        $this->blueprint->multiPoint('col');
    }

    public function testMultiLineString(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multilinestring', 'col')
            ->once();

        $this->blueprint->multiLineString('col');
    }

    public function testMultiPolygon(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('multipolygon', 'col')
            ->once();

        $this->blueprint->multiPolygon('col');
    }

    public function testGeometryCollection(): void
    {
        $this->blueprint
            ->shouldReceive('addColumn')
            ->with('geometrycollection', 'col')
            ->once();

        $this->blueprint->geometryCollection('col');
    }

    public function testEnablePostgis(): void
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('enablePostgis')
            ->once();

        $this->blueprint->enablePostgis();
    }

    public function testDisablePostgis(): void
    {
        $this->blueprint
            ->shouldReceive('addCommand')
            ->with('disablePostgis')
            ->once();

        $this->blueprint->disablePostgis();
    }
}
