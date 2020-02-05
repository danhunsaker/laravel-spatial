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

	/**
	 * @inheritDoc
	 */
	public static function assertStringContainsStringIgnoringCase(string $needle, string $haystack, string $message = ''): void
	{
		if (method_exists(parent::class, 'assertStringContainsStringIgnoringCase')) {
			parent::assertStringContainsStringIgnoringCase($needle, $haystack, $message);
		} else {
			self::assertContains($needle, $haystack, $message, true);
		}
	}

	/**
	 * @inheritDoc
	 */
	public static function assertStringContainsString(string $needle, string $haystack, string $message = ''): void
	{
		if (method_exists(parent::class, 'assertStringContainsString')) {
			parent::assertStringContainsString($needle, $haystack, $message);
		} else {
			self::assertContains($needle, $haystack, $message);
		}
	}
}
