<?php

namespace Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTestCase
 * @package Tests\Unit
 */
abstract class BaseTestCase extends TestCase
{
	use MockeryPHPUnitIntegration;

    public function tearDown(): void
    {
        Mockery::close();
    }
}
