<?php namespace LaravelSpatial;

use LaravelSpatial\Schema\Grammars\PostgresGrammar;
use LaravelSpatial\Schema\PostgresBuilder;

/**
 * Class PostgresConnection
 *
 * @package LaravelSpatial
 */
class PostgresConnection extends \Illuminate\Database\PostgresConnection
{
    /**
     * @inheritDoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        // Prevent geography type fields from throwing a 'type not found' error.
        $this->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geography', 'string');
    }

    /**
     * @inheritDoc
     */
    public function getSchemaBuilder()
    {
        if ($this->schemaGrammar === null) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new PostgresGrammar());
    }
}
