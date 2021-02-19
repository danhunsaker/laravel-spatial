<?php

namespace LaravelSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Fluent;
use LaravelSpatial\Schema\Grammars\GrammarFactory;

/**
 * Class MultiPoint
 *
 * @package LaravelSpatial\Doctrine
 */
class MultiPoint extends Type
{
    public const MULTIPOINT = 'multipoint';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return GrammarFactory::make($platform->getName())->typeMultipoint(new Fluent);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::MULTIPOINT;
    }
}
