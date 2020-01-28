<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class Polygon
 *
 * @package LaravelSpatial\Doctrine
 */
class Polygon extends Type
{
    const POLYGON = 'polygon';

	/**
	 * @inheritDoc
	 */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
	    return GrammarFactory::make($platform->getName())->typePolygon(new Fluent);
    }

	/**
	 * @inheritDoc
	 */
    public function getName()
    {
        return self::POLYGON;
    }
}
