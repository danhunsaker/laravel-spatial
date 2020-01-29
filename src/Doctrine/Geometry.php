<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class Geometry
 *
 * @package LaravelSpatial\Doctrine
 */
class Geometry extends Type
{
    const GEOMETRY = 'geometry';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return GrammarFactory::make($platform->getName())->typeGeometry(new Fluent);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::GEOMETRY;
    }
}
