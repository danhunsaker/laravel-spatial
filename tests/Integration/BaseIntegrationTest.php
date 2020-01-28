<?php

namespace Tests\Integration;

use Closure;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\QueryException;
use LaravelSpatial\Exceptions\SpatialFieldsNotDefinedException;
use LaravelSpatial\SpatialServiceProvider;
use GeoJson\Geometry\GeometryCollection;
use GeoJson\Geometry\LineString;
use GeoJson\Geometry\MultiPoint;
use GeoJson\Geometry\MultiPolygon;
use GeoJson\Geometry\Point;
use GeoJson\Geometry\Polygon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Integration\Models\GeometryModel;
use Tests\Integration\Models\NoSpatialFieldsModel;
use Tests\Integration\Tools\ClassFinder;

/**
 * Class BaseIntegrationTest
 * @package Tests\Integration
 */
abstract class BaseIntegrationTest extends BaseTestCase
{
	/**
	 * @var bool
	 */
	protected $is_postgres = false;

	/**
	 * @var bool|int
	 */
	protected $after_fix = false;

	/**
	 * Boots the application.
	 *
	 * @return \Illuminate\Foundation\Application|\Illuminate\Contracts\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';
		$app->register(SpatialServiceProvider::class);
		$app->make(Kernel::class)->bootstrap();

		$this->setupDatabaseConfig($app);

		return $app;
	}

	/**
	 * Setup database specific configuration.
	 *
	 * @param \Illuminate\Contracts\Foundation\Application $app
	 */
	abstract protected function setupDatabaseConfig($app): void;

	/**
	 * Check if mysql version is after bug fixes.
	 *
	 * - MySQL 8.0.4 fixed bug #26941370 and bug #88031
	 *
	 * @return bool|int
	 */
	abstract protected function isMySQL8AfterFix();

	/**
	 * Setup DB before each test.
	 *
	 * @return void
	 */
	public function setUp(): void
	{
		parent::setUp();

		$this->after_fix = $this->isMySQL8AfterFix();

		$this->onMigrations(function($migrationClass) {
			(new $migrationClass())->up();
		});
	}

	public function tearDown(): void
	{
		try{
			$this->onMigrations(function($migrationClass) {
				(new $migrationClass())->down();
			}, true);
		}catch(\Exception $e){
			$connection = $this->getConnection();
			$connection->getSchemaBuilder()->dropAllTables();
		}

		parent::tearDown();
	}

	/**
	 * @param \Closure $closure
	 * @param bool     $reverse_sort
	 */
	private function onMigrations(Closure $closure, $reverse_sort = false): void
	{
		$fileSystem = new Filesystem();
		$classFinder = new ClassFinder();

		$migrations = $fileSystem->files(__DIR__ . '/Migrations');
		$reverse_sort ? rsort($migrations, SORT_STRING) : sort($migrations, SORT_STRING);

		foreach ($migrations as $file) {
			$fileSystem->requireOnce($file);
			$migrationClass = $classFinder->findClass($file);

			$closure($migrationClass);
		}
	}

	public function testSpatialFieldsNotDefinedException(): void
	{
		$this->expectException(SpatialFieldsNotDefinedException::class);

		$geo = new NoSpatialFieldsModel();
		$geo->geometry = new Point([1, 2]);
		$geo->save();

		NoSpatialFieldsModel::all();
	}

	public function testInsertPoint(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testInsertLineString(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->line = new LineString([new Point([1, 1]), new Point([2, 2])]);
		$geo->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testInsertPolygon(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->shape = new Polygon([[[0, 10], [10, 10], [10, 0], [0, 0], [0, 10]]]);
		$geo->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testInsertMultiPoint(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->multi_locations = new MultiPoint([new Point([1, 1]), new Point([2, 2])]);
		$geo->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testInsertMultiPolygon(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->multi_shapes = new MultiPolygon([
												  new Polygon([[[0, 10], [10, 10], [10, 0], [0, 0], [0, 10]]]),
												  new Polygon([[[0, 0], [0, 5], [5, 5], [5, 0], [0, 0]]]),
											  ]);
		$geo->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testInsertGeometryCollection(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->multi_geometries = new GeometryCollection([
															new Polygon([[[0, 10], [10, 10], [10, 0], [0, 0], [0, 10]]]),
															new Polygon([[[0, 0], [0, 5], [5, 5], [5, 0], [0, 0]]]),
															new Point([0, 0]),
														]);
		$geo->save()
		;
		$this->assertDatabaseHas($geo->getTable(), ['id' => $geo->id]);
	}

	public function testUpdate(): void
	{
		$geo = new GeometryModel();
		$geo->location = new Point([1, 2]);
		$geo->save();

		$to_update = GeometryModel::all()->first();
		$to_update->location = new Point([2, 3]);
		$to_update->save();

		$this->assertDatabaseHas($geo->getTable(), ['id' => $to_update->id]);

		$all = GeometryModel::all();
		$this->assertCount(1, $all);

		$updated = $all->first();
		$this->assertInstanceOf(Point::class, $updated->location);
		$this->assertEquals(2, $updated->location->getCoordinates()[0]);
		$this->assertEquals(3, $updated->location->getCoordinates()[1]);
	}

	public function testDistance(): void
	{
		$loc1 = new GeometryModel();
		$loc1->location = new Point([1, 1]);
		$loc1->save();

		$loc2 = new GeometryModel();
		$loc2->location = new Point([2, 2]); // Distance from loc1: 1.4142135623731
		$loc2->save();

		$loc3 = new GeometryModel();
		$loc3->location = new Point([3, 3]); // Distance from loc1: 2.8284271247462
		$loc3->save();

		$a = GeometryModel::distance('location', $loc1->location, 2)->get();
		$this->assertCount(2, $a);
		$this->assertTrue($a->contains('location', $loc1->location));
		$this->assertTrue($a->contains('location', $loc2->location));
		$this->assertFalse($a->contains('location', $loc3->location));

		// Excluding self
		$b = GeometryModel::distanceExcludingSelf('location', $loc1->location, 2)->get();
		$this->assertCount(1, $b);
		$this->assertFalse($b->contains('location', $loc1->location));
		$this->assertTrue($b->contains('location', $loc2->location));
		$this->assertFalse($b->contains('location', $loc3->location));

		$c = GeometryModel::distance('location', $loc1->location, 1)->get();
		$this->assertCount(1, $c);
		$this->assertTrue($c->contains('location', $loc1->location));
		$this->assertFalse($c->contains('location', $loc2->location));
		$this->assertFalse($c->contains('location', $loc3->location));
	}

	public function testDistanceSphere(): void
	{
		$loc1 = new GeometryModel();
		$loc1->location = new Point([-73.971732, 40.767864]);
		$loc1->save();

		$loc2 = new GeometryModel();
		$loc2->location = new Point([-73.971271, 40.767664]); // Distance from loc1: 44.741406484588
		$loc2->save();

		$loc3 = new GeometryModel();
		$loc3->location = new Point([-73.977619, 40.761434]); // Distance from loc1: 870.06424066202
		$loc3->save();

		try {
			$a = GeometryModel::distanceSphere('location', $loc1->location, 200)->get();
			$this->assertCount(2, $a);
			$this->assertTrue($a->contains('location', $loc1->location));
			$this->assertTrue($a->contains('location', $loc2->location));
			$this->assertFalse($a->contains('location', $loc3->location));

			// Excluding self
			$b = GeometryModel::distanceSphereExcludingSelf('location', $loc1->location, 200)->get();
			$this->assertCount(1, $b);
			$this->assertFalse($b->contains('location', $loc1->location));
			$this->assertTrue($b->contains('location', $loc2->location));
			$this->assertFalse($b->contains('location', $loc3->location));

			if ($this->is_postgres || $this->after_fix) {
				$c = GeometryModel::distanceSphere('location', $loc1->location, 44.741406484236)->get();
			} else {
				$c = GeometryModel::distanceSphere('location', $loc1->location, 44.741406484587)->get();
			}
			$this->assertCount(1, $c);
			$this->assertTrue($c->contains('location', $loc1->location));
			$this->assertFalse($c->contains('location', $loc2->location));
			$this->assertFalse($c->contains('location', $loc3->location));
		} catch (QueryException $e) {
			if (strpos($e->getMessage(), 'FUNCTION spatial_test.ST_Distance_Sphere does not exist') > -1) {
				$this->markTestSkipped('Spherical distance tests [distanceSphere*()] not supported on the current DBMS');
			}
			throw $e;
		}
	}

	public function testDistanceValue(): void
	{
		$loc1 = new GeometryModel();
		$loc1->location = new Point([1, 1]);
		$loc1->save();

		$loc2 = new GeometryModel();
		$loc2->location = new Point([2, 2]); // Distance from loc1: 1.4142135623731
		$loc2->save();

		$a = GeometryModel::distanceValue('location', $loc1->location)->get();
		$this->assertCount(2, $a);
		$this->assertEquals(0, $a[0]->distance);
		$this->assertEquals(1.4142135623, $a[1]->distance); // PHP floats' 11th+ digits don't matter
	}

	public function testDistanceSphereValue(): void
	{
		$loc1 = new GeometryModel();
		$loc1->location = new Point([-73.971732, 40.767864]);
		$loc1->save();

		$loc2 = new GeometryModel();
		$loc2->location = new Point([-73.971271, 40.767664]); // Distance from loc1: 44.741406484236
		$loc2->save();

		try {
			$a = GeometryModel::distanceSphereValue('location', $loc1->location)->get();
			$this->assertCount(2, $a);
			$this->assertEquals(0, $a[0]->distance);

		} catch (QueryException $e) {
			if (strpos($e->getMessage(), 'FUNCTION spatial_test.ST_Distance_Sphere does not exist') > -1) {
				$this->markTestSkipped('Spherical distance tests [distanceSphere*()] not supported on the current DBMS');
			}
			throw $e;
		}

		if ($this->is_postgres) {
			$this->assertEquals('44.7415664', number_format($a[1]->distance, 7)); // Postgres calculates this differently?
		} elseif ($this->after_fix) {
			$this->assertEquals(44.7414064842, $a[1]->distance); // PHP floats' 11th+ digits don't matter
		} else {
			$this->assertEquals(44.7414064845, $a[1]->distance); // PHP floats' 11th+ digits don't matter
		}
	}
}