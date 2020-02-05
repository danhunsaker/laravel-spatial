<?php

namespace Tests\Integration;

use Illuminate\Support\Facades\DB;

/**
 * Class MysqlTest
 */
class MysqlTest extends BaseIntegrationTest
{
    /**
     * Setup database specific configuration.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    protected function setupDatabaseConfig($app): void
    {
        $host = env('MYSQL_HOST', env('DB_HOST', '127.0.0.1'));

        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $app['config'];

        $config->set('database.default', 'mysql');
        $config->set('database.connections.mysql.host', $host);
        $config->set('database.connections.mysql.database', 'spatial_test');
        $config->set('database.connections.mysql.username', 'root');
        $config->set('database.connections.mysql.password', '');
        $config->set('database.connections.mysql.modes', [
            'ONLY_FULL_GROUP_BY',
            'STRICT_TRANS_TABLES',
            'NO_ZERO_IN_DATE',
            'NO_ZERO_DATE',
            'ERROR_FOR_DIVISION_BY_ZERO',
            'NO_ENGINE_SUBSTITUTION',
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function isMySQL8AfterFix()
    {
        $results = DB::select(DB::raw('select version()'));
        $mysql_version = $results[0]->{'version()'};

        return version_compare($mysql_version, '8.0.4', '>=');
    }

    //public function testBounding() {
    //    $point = new Point([0, 0]);
    //
    //    $linestring1 = \GeoJson\Geometry\LineString::fromWkt("LINESTRING(1 1, 2 2)");
    //    $linestring2 = \GeoJson\Geometry\LineString::fromWkt("LINESTRING(20 20, 24 24)");
    //    $linestring3 = \GeoJson\Geometry\LineString::fromWkt("LINESTRING(0 10, 10 10)");
    //
    //    $geo1 = new GeometryModel();
    //    $geo1->location = $point;
    //    $geo1->line = $linestring1;
    //    $geo1->save();
    //
    //    $geo2 = new GeometryModel();
    //    $geo2->location = $point;
    //    $geo2->line = $linestring2;
    //    $geo2->save();
    //
    //    $geo3 = new GeometryModel();
    //    $geo3->location = $point;
    //    $geo3->line = $linestring3;
    //    $geo3->save();
    //
    //    $polygon = new Polygon([[[0, 10],[10, 10],[10, 0],[0, 0],[0, 10]]]);
    //
    //    $result = GeometryModel::Bounding($polygon, 'line')->get();
    //    $this->assertCount(2, $result);
    //    $this->assertTrue($result->contains($geo1));
    //    $this->assertFalse($result->contains($geo2));
    //    $this->assertTrue($result->contains($geo3));
    //
    //}
}
