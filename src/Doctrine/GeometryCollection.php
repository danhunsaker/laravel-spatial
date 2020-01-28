<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class GeometryCollection
 *
 * @package LaravelSpatial\Doctrine
 */
class GeometryCollection extends Type
{
    const GEOMETRYCOLLECTION = 'geometrycollection';

	/**
	 * @inheritDoc
	 */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
	    return GrammarFactory::make($platform->getName())->typeGeometrycollection(new Fluent);
    }

	/**
	 * @inheritDoc
	 */
    public function getName()
    {
        return self::GEOMETRYCOLLECTION;
    }
}
