<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class MultiLineString
 *
 * @package LaravelSpatial\Doctrine
 */
class MultiLineString extends Type
{
    public const MULTILINESTRING = 'multilinestring';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return GrammarFactory::make($platform->getName())->typeMultilinestring(new Fluent);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::MULTILINESTRING;
    }
}
