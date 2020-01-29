<?php

namespace Tests\Unit\Schema\Grammars;

use \InvalidArgumentException;
use LaravelSpatial\Schema\Grammars\GrammarFactory;
use LaravelSpatial\Schema\Grammars\MySqlGrammar;
use LaravelSpatial\Schema\Grammars\PostgresGrammar;
use Tests\Unit\BaseTestCase;

/**
 * Class GrammarFactoryTest
 *
 * @package Tests\Unit\Schema\Grammars
 *
 * @coversDefaultClass \LaravelSpatial\Schema\Grammars\GrammarFactory
 */
class GrammarFactoryTest extends BaseTestCase
{

    /**
     * @covers ::make
     */
    public function testReturnMysqlGrammar(): void
    {
        $result = GrammarFactory::make('mysql');

        $this->assertInstanceOf(MySqlGrammar::class, $result);
    }

    /**
     * @covers ::make
     */
    public function testReturnPostgresGrammar(): void
    {
        $result = GrammarFactory::make('postgresql');

        $this->assertInstanceOf(PostgresGrammar::class, $result);
    }

    /**
     * @covers ::make
     */
    public function testThrowOnUnsupported(): void
    {
        $this->expectException(InvalidArgumentException::class);

        GrammarFactory::make('not a real platform');
    }
}
