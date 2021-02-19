<?php

namespace Tests\Integration;

use Illuminate\Contracts\Foundation\Application;

/**
 * Class PostgresTest
 * @package Tests\Integration
 */
class PostgresTest extends BaseIntegrationTest
{
    /**
     * @inheritDoc
     */
    protected bool $is_postgres = true;

    /**
     * Setup database specific configuration.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function setupDatabaseConfig(Application $app): void
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
