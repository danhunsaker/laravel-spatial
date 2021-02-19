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

    public function getAttribute($attribute)
    {
        if ($attribute === parent::ATTR_SERVER_VERSION) {
            return null;
        }

        parent::getAttribute($attribute); // TODO: Change the autogenerated stub
    }
}
