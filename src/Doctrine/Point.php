<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class Point
 *
 * @package LaravelSpatial\Doctrine
 */
class Point extends Type
{
    const POINT = 'point';

	/**
	 * @inheritDoc
	 */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
	    return GrammarFactory::make($platform->getName())->typePoint(new Fluent);
    }

	/**
	 * @inheritDoc
	 */
    public function getName()
    {
        return self::POINT;
    }
}
