<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class LineString
 *
 * @package LaravelSpatial\Doctrine
 */
class LineString extends Type
{
    public const LINESTRING = 'linestring';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return GrammarFactory::make($platform->getName())->typeLinestring(new Fluent);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::LINESTRING;
    }
}
