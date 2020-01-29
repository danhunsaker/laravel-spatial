<?php

namespace Tests\Unit\Schema;

use LaravelSpatial\PostgresConnection;
use LaravelSpatial\Schema\Blueprint;
use LaravelSpatial\Schema\PostgresBuilder;
use Mockery;
use Tests\Unit\BaseTestCase;

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

        /** @var PostgresBuilder|\Mockery\MockInterface $mock */
        $mock = Mockery::mock(PostgresBuilder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
