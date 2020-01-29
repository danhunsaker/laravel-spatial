<?php

namespace Tests\Integration;

/**
 * Class PostgresTest
 * @package Tests\Integration
 */
class PostgresTest extends BaseIntegrationTest
{
    /**
     * @inheritDoc
     */
    protected $is_postgres = true;

    /**
     * Setup database specific configuration.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function setupDatabaseConfig($app): void
    {
        $host = env('POSTGRES_HOST', env('DB_HOST', '127.0.0.1'));

        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $app['config'];

        $config->set('database.default', 'pgsql');
        $config->set('database.connections.pgsql.host', $host);
        $config->set('database.connections.pgsql.database', 'spatial_test');
        $config->set('database.connections.pgsql.username', 'postgres');
        $config->set('database.connections.pgsql.password', '');
    }

    /**
     * @inheritDoc
     */
    protected function isMySQL8AfterFix(): bool
    {
        return false;
    }
}
