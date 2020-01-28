<?php

namespace Tests\Unit\Stubs;

/**
 * Class PDOStub
 *
 * Test pdo stub that won't initialize connection.
 *
 * @package Tests\Unit\Stubs
 */
class PDOStub extends \PDO
{
    public function __construct()
    {
    }
}
