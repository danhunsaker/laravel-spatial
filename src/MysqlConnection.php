<?php

namespace LaravelSpatial;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\MySqlConnection as BaseMysqlConnection;
use LaravelSpatial\Schema\Grammars\MySqlGrammar;

/**
 * Class MysqlConnection
 *
 * @package LaravelSpatial
 */
class MysqlConnection extends BaseMysqlConnection
{
    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
        if (class_exists(Type::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry',
                'point',
                'linestring',
                'polygon',
                'multipoint',
                'multilinestring',
                'multipolygon',
                'geomcollection',
                'geometrycollection',
            ];
            $dbPlatform = $this->getDoctrineSchemaManager()->getDatabasePlatform();
            foreach ($geometries as $type) {
                $dbPlatform->registerDoctrineTypeMapping($type, 'string');
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new MySqlGrammar());
    }
}
