<?php

namespace Tests\Unit\Eloquent;

use GeoJson\Geometry\GeometryCollection;
use GeoJson\Geometry\LineString;
use GeoJson\Geometry\MultiLineString;
use GeoJson\Geometry\MultiPoint;
use GeoJson\Geometry\MultiPolygon;
use GeoJson\Geometry\Polygon;
use GeoJson\Geometry\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use LaravelSpatial\Eloquent\Builder;
use LaravelSpatial\Eloquent\SpatialTrait;
use LaravelSpatial\Exceptions\SpatialFieldsNotDefinedException;
use LaravelSpatial\MysqlConnection;
use Mockery;
use Tests\Unit\BaseTestCase;

/**
 * Class SpatialTraitTest
 * @package Tests\Unit\Eloquent
 */
class SpatialTraitTest extends BaseTestCase
{
    /**
     * @var TestModel
     */
    protected $model;

    /**
     * @var array
     */
    protected $queries;

    public function setUp(): void
    {
        $this->model = new TestModel();
        $this->queries = &$this->model->getConnection()->getPdo()->queries;
    }

    public function tearDown(): void
    {
        $this->model->getConnection()->getPdo()->resetQueries();
    }

    public function testInsertUpdatePointHasCorrectSql(): void
	{
        $this->assertFalse($this->model->exists);

        $this->model->point = new Point([1, 2]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`point`) values (ST_GeomFromText('POINT (1 2)'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->point = new Point([1, 2]);
        $this->model->save();

        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `point` = ST_GeomFromText('POINT (1 2)') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdateLineStringHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $point2 = new Point([2, 3]);

        $this->assertFalse($this->model->exists);

        $this->model->linestring = new LineString([$point1, $point2]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`linestring`) values (ST_GeomFromText('LINESTRING (1 2, 2 3)'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->linestring = new LineString([$point1, $point2]);
        $this->model->save();

        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `linestring` = ST_GeomFromText('LINESTRING (1 2, 2 3)') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdatePolygonHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $point2 = new Point([2, 3]);
        $point3 = new Point([3, 2]);
        $point4 = new Point([2, 1]);

        $this->assertFalse($this->model->exists);

        $this->model->polygon = new Polygon([[$point1, $point2, $point3, $point4, $point1]]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`polygon`) values (ST_GeomFromText('POLYGON ((1 2, 2 3, 3 2, 2 1, 1 2))'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->polygon = new Polygon([[$point1, $point2, $point3, $point4, $point1]]);
        $this->model->save();
        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `polygon` = ST_GeomFromText('POLYGON ((1 2, 2 3, 3 2, 2 1, 1 2))') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdateMultiPointHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $point2 = new Point([2, 3]);

        $this->assertFalse($this->model->exists);

        $this->model->multipoint = new MultiPoint([$point1, $point2]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`multipoint`) values (ST_GeomFromText('MULTIPOINT ((1 2), (2 3))'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->multipoint = new MultiPoint([$point1, $point2]);
        $this->model->save();

        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `multipoint` = ST_GeomFromText('MULTIPOINT ((1 2), (2 3))') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdateMultiLineStringHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $point2 = new Point([2, 3]);
        $linestring1 = new LineString([$point1, $point2]);
        $point3 = new Point([3, 2]);
        $point4 = new Point([2, 1]);
        $linestring2 = new LineString([$point3, $point4]);

        $this->assertFalse($this->model->exists);

        $this->model->multilinestring = new MultiLineString([$linestring1, $linestring2]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`multilinestring`) values (ST_GeomFromText('MULTILINESTRING ((1 2, 2 3), (3 2, 2 1))'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->multilinestring = new MultiLineString([$linestring1, $linestring2]);
        $this->model->save();
        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `multilinestring` = ST_GeomFromText('MULTILINESTRING ((1 2, 2 3), (3 2, 2 1))') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdateMultiPolygonHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $point2 = new Point([2, 3]);
        $point3 = new Point([3, 2]);
        $point4 = new Point([2, 1]);
        $point5 = new Point([4, 5]);
        $point6 = new Point([5, 6]);
        $point7 = new Point([6, 5]);
        $point8 = new Point([5, 4]);

        $polygon1 = new Polygon([[$point1, $point2, $point3, $point4, $point1]]);
        $polygon2 = new Polygon([[$point5, $point6, $point7, $point8, $point5]]);

        $this->assertFalse($this->model->exists);

        $this->model->multipolygon = new MultiPolygon([$polygon1, $polygon2]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`multipolygon`) values (ST_GeomFromText('MULTIPOLYGON (((1 2, 2 3, 3 2, 2 1, 1 2)), ((4 5, 5 6, 6 5, 5 4, 4 5)))'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->multipolygon = new MultiPolygon([$polygon1, $polygon2]);
        $this->model->save();
        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `multipolygon` = ST_GeomFromText('MULTIPOLYGON (((1 2, 2 3, 3 2, 2 1, 1 2)), ((4 5, 5 6, 6 5, 5 4, 4 5)))') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testInsertUpdateGeometryCollectionHasCorrectSql(): void
	{
        $point1 = new Point([1, 2]);
        $linestring1 = new LineString([[2, 3], [3, 3]]);

        $this->assertFalse($this->model->exists);

        $this->model->geometrycollection = new GeometryCollection([$point1, $linestring1]);
        $this->model->save();

        $this->assertStringStartsWith('insert', $this->queries[0]);
        $this->assertStringContainsString("insert into `test_models` (`geometrycollection`) values (ST_GeomFromText('GEOMETRYCOLLECTION (POINT (1 2), LINESTRING (2 3, 3 3))'))", $this->queries[0]);
        // TODO: assert bindings in query
        $this->assertTrue($this->model->exists);

        $this->model->geometrycollection = new GeometryCollection([$point1, $linestring1]);
        $this->model->save();
        $this->assertStringStartsWith('update', $this->queries[1]);
        $this->assertStringContainsString("update `test_models` set `geometrycollection` = ST_GeomFromText('GEOMETRYCOLLECTION (POINT (1 2), LINESTRING (2 3, 3 3))') where `id` = ?", $this->queries[1]);
        // TODO: assert bindings in query
    }

    public function testSettingRawAttributes(): void
	{
        $attributes['point'] = pack('H*', '0101000000000000000000f03f0000000000000040');

        $this->model->setRawAttributes($attributes);
        $this->assertInstanceOf(Point::class, ($this->model->point));
    }

    public function testSpatialFieldsNotDefinedException(): void
	{
        $model = new TestNoSpatialModel();
        $this->expectException(SpatialFieldsNotDefinedException::class);
        $model->getSpatialFields();
    }

    public function testScopeDistance(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distance('point', $point, 10);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('ST_Distance(point, ST_GeomFromText(?)) <= ?', $q->wheres[0]['sql']);
        $this->assertEquals('POINT (1 2)', $bindings[0]);
        $this->assertEquals(10, $bindings[1]);
    }

    public function testScopeDistanceExcludingSelf(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distanceExcludingSelf('point', $point, 10);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('ST_Distance(point, ST_GeomFromText(?)) <= ?', $q->wheres[0]['sql']);
        $this->assertEquals('ST_Distance(point, ST_GeomFromText(?)) != 0', $q->wheres[1]['sql']);
        $this->assertEquals('POINT (1 2)', $bindings[0]);
        $this->assertEquals(10, $bindings[1]);
        $this->assertEquals('POINT (1 2)', $bindings[2]);
    }

    public function testScopeDistanceSphere(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distanceSphere('point', $point, 10);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('ST_Distance_Sphere(point, ST_GeomFromText(?)) <= ?', $q->wheres[0]['sql']);
        $this->assertEquals('POINT (1 2)', $bindings[0]);
        $this->assertEquals(10, $bindings[1]);
    }

    public function testScopeDistanceSphereExcludingSelf(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distanceSphereExcludingSelf('point', $point, 10);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('ST_Distance_Sphere(point, ST_GeomFromText(?)) <= ?', $q->wheres[0]['sql']);
        $this->assertEquals('ST_Distance_Sphere(point, ST_GeomFromText(?)) != 0', $q->wheres[1]['sql']);
        $this->assertEquals('POINT (1 2)', $bindings[0]);
        $this->assertEquals(10, $bindings[1]);
        $this->assertEquals('POINT (1 2)', $bindings[2]);
    }

    public function testScopeDistanceValue(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distanceValue('point', $point);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->columns);
        $bindings = $q->getRawBindings()['select'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('*', $q->columns[0]);
        $this->assertInstanceOf(Expression::class, $q->columns[1]);
        $this->assertEquals('ST_Distance(point, ST_GeomFromText(?)) as distance', $q->columns[1]->getValue());
        $this->assertEquals('POINT (1 2)', $bindings[0]);
    }

    public function testScopeDistanceValueWithSelect(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::select('some_column')->distanceValue('point', $point);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->columns);
        $bindings = $q->getRawBindings()['select'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('some_column', $q->columns[0]);
        $this->assertInstanceOf(Expression::class, $q->columns[1]);
        $this->assertEquals('ST_Distance(point, ST_GeomFromText(?)) as distance', $q->columns[1]->getValue());
        $this->assertEquals('POINT (1 2)', $bindings[0]);
    }

    public function testScopeDistanceSphereValue(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::distanceSphereValue('point', $point);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->columns);
        $bindings = $q->getRawBindings()['select'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('*', $q->columns[0]);
        $this->assertInstanceOf(Expression::class, $q->columns[1]);
        $this->assertEquals('ST_Distance_Sphere(point, ST_GeomFromText(?)) as distance', $q->columns[1]->getValue());
        $this->assertEquals('POINT (1 2)', $bindings[0]);
    }

    public function testScopeDistanceSphereValueWithSelect(): void
	{
        $point = new Point([1, 2]);
        $query = TestModel::select('some_column')->distanceSphereValue('point', $point);

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->columns);
        $bindings = $q->getRawBindings()['select'];
        $this->assertNotEmpty($bindings);
        $this->assertEquals('some_column', $q->columns[0]);
        $this->assertInstanceOf(Expression::class, $q->columns[1]);
        $this->assertEquals('ST_Distance_Sphere(point, ST_GeomFromText(?)) as distance', $q->columns[1]->getValue());
        $this->assertEquals('POINT (1 2)', $bindings[0]);
    }

	/**
	 * @return \GeoJson\Geometry\Polygon
	 */
	private function buildTestPolygon(): Polygon
	{
        $point1 = new Point([1, 1]);
        $point2 = new Point([1, 2]);
        $point3 = new Point([1, 2]);
        $point4 = new Point([2, 2]);
        $point5 = new Point([2, 2]);
        $point6 = new Point([1, 1]);

        return new Polygon([[$point1, $point2, $point3, $point4, $point5, $point6]]);
    }

    public function testScopeComparison(): void
	{
        $query = TestModel::comparison('point', $this->buildTestPolygon(), 'within');

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Within(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeWithin(): void
	{
        $query = TestModel::within('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Within(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeCrosses(): void
	{
        $query = TestModel::crosses('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Crosses(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeContains(): void
	{
        $query = TestModel::contains('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Contains(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeDisjoint(): void
	{
        $query = TestModel::disjoint('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Disjoint(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeEquals(): void
	{
        $query = TestModel::equals('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Equals(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeIntersects(): void
	{
        $query = TestModel::intersects('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Intersects(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeOverlaps(): void
	{
        $query = TestModel::overlaps('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Overlaps(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }

    public function testScopeDoesTouch(): void
	{
        $query = TestModel::doesTouch('point', $this->buildTestPolygon());

        $this->assertInstanceOf(Builder::class, $query);
        $q = $query->getQuery();
        $this->assertNotEmpty($q->wheres);
        $bindings = $q->getRawBindings()['where'];
        $this->assertNotEmpty($bindings);
        $this->assertStringContainsString('ST_Touches(`point`, ST_GeomFromText(?))', $q->wheres[0]['sql']);
        $this->assertEquals('POLYGON ((1 1, 1 2, 1 2, 2 2, 2 2, 1 1))', $bindings[0]);
    }
}

/**
 * Test Model
 *
 * @property $point
 * @property $linestring
 * @property $polygon
 * @property $multipoint
 * @property $multilinestring
 * @property $multipolygon
 * @property $geometrycollection
 *
 * @method static static|Builder select($column)
 */
class TestModel extends Model
{
    use SpatialTrait;

    protected $spatialFields = [
        'point',
        'linestring',
        'polygon',
        'multipoint',
        'multilinestring',
        'multipolygon',
        'geometrycollection',
    ];

    public $timestamps = false;

    public static $pdo;

    public static function resolveConnection($connection = null)
    {
        if (is_null(static::$pdo)) {
            static::$pdo = Mockery::mock(TestPDO::class)->makePartial();
        }

        return new MysqlConnection(static::$pdo);
    }

    public function testrelatedmodels(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
        return $this->hasMany(TestRelatedModel::class);
    }

    public function testrelatedmodels2(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
        return $this->belongsToMany(TestRelatedModel::class);
    }
}

class TestRelatedModel extends TestModel
{
    public function testmodel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
        return $this->belongsTo(TestModel::class);
    }

    public function testmodels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
        return $this->belongsToMany(TestModel::class);
    }
}

class TestNoSpatialModel extends Model
{
    use SpatialTrait;
}

class TestPDO extends \PDO
{
    public $queries = [];

    public $counter = 1;

    public function prepare($statement, $driver_options = [])
    {
        $this->queries[] = $statement;

        $stmt = Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('bindValue')->zeroOrMoreTimes();
        $stmt->shouldReceive('execute');
        $stmt->shouldReceive('fetchAll')->andReturn([['id' => 1, 'point' => 'POINT(1 2)']]);
        $stmt->shouldReceive('rowCount')->andReturn(1);

        return $stmt;
    }

    public function lastInsertId($name = null): int
	{
        return $this->counter++;
    }

    public function resetQueries(): void
	{
        $this->queries = [];
    }
}
