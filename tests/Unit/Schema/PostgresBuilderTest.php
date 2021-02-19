<?php

namespace Tests\Unit\Schema;

use LaravelSpatial\PostgresConnection;
use LaravelSpatial\Schema\Blueprint;
use LaravelSpatial\Schema\PostgresBuilder;
use Mockery;
use Tests\Unit\BaseTestCase;
use Tests\Unit\Stubs\PostgresBuilderStub;

/**
 * Class BuilderTest
 *
 * @package Tests\Unit\Schema
 */
class PostgresBuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint(): void
    {
        $connection = Mockery::mock(PostgresConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = new PostgresBuilderStub($connection);
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
