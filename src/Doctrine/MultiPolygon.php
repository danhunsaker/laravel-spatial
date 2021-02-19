<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class MultiPolygon
 *
 * @package LaravelSpatial\Doctrine
 */
class MultiPolygon extends Type
{
	public const MULTIPOLYGON = 'multipolygon';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return GrammarFactory::make($platform->getName())->typeMultipolygon(new Fluent());
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::MULTIPOLYGON;
    }
}
