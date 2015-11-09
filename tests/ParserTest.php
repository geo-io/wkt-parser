<?php

namespace GeoIO\WKT\Parser;

use GeoIO\Dimension;
use Mockery;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected function coords($x, $y, $z = null, $m = null)
    {
        return array(
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'm' => $m
        );
    }

    public function testPoint()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2)');
    }

    public function testPointEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, null, null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT EMPTY');
    }

    public function testPoint3Coords()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 2, 3), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2 3)');
    }

    public function testPointWith4Coords()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(1, 2, 3, 4), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2 3 4)');
    }

    public function testPointZ()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 2, 3), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT Z(1 2 3)');
    }

    public function testPointM()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DM, $this->coords(1, 2, null, 3), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT M(1 2 3)');
    }

    public function testPointZM()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(1, 2, 3, 4), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT ZM(1 2 3 4)');
    }

    public function testPointWithSrid()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), 1234)
        ;

        $parser = new Parser($factory);
        $parser->parse('SRID=1234;POINT(1 2)');
    }

    // ---

    public function testLineString()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(5, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2, 3 4, 5 6)');
    }

    public function testLineStringEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING EMPTY');
    }

    public function testLineString3Coords()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 2, 3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(4, 5, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3, 4 5 6)');
    }

    public function testLineStringWith4Coords()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_4D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(1, 2, 3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(5, 6, 7, 8), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3 4, 5 6 7 8)');
    }

    public function testLineStringZ()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 2, 3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(4, 5, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING Z(1 2 3, 4 5 6)');
    }

    public function testLineStringM()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_3DM, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DM, $this->coords(1, 2, null, 3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DM, $this->coords(4, 5, null, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING M(1 2 3, 4 5 6)');
    }

    public function testLineStringZM()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_4D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(1, 2, 3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_4D, $this->coords(5, 6, 7, 8), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('LINESTRING ZM(1 2 3 4, 5 6 7 8)');
    }

    public function testLineStringWithSrid()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), 1234)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), 1234)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(3, 4), 1234)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(5, 6), 1234)
        ;

        $parser = new Parser($factory);
        $parser->parse('SRID=1234;LINESTRING(1 2, 3 4, 5 6)');
    }

    public function testLineStringWithInconsistentCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $factory = Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing();

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3, 4 5,7 8 9)');
    }

    // ---

    public function testPolygon()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPolygon')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createLinearRing')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->times(2)
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(5, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POLYGON((1 2, 3 4, 5 6, 1 2))');
    }

    public function testPolygonEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPolygon')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POLYGON EMPTY');
    }

    public function testPolygonWithHoles()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPolygon')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createLinearRing')
            ->times(2)
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 0, -1), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(10, 0, -2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(10, 10, -3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 10, -4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 0, -5), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 1, -6), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(2, 3, -7), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(3, 1, -8), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 1, -9), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POLYGON((0 0 -1, 10 0 -2, 10 10 -3, 0 10 -4, 0 0 -5),(1 1 -6, 2 3 -7, 3 1 -8, 1 1 -9))');
    }

    // ---

    public function testParseMultiPoint()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(0, 3), null)
        ;

        $parser = new Parser($factory);
        $parser->parse("MULTIPOINT((1 2), (0 3))");
    }

    public function testParseMultiPointNonStandard()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(0, 3), null)
        ;

        $parser = new Parser($factory);
        $parser->parse("MULTIPOINT(1 2, 0 3)");
    }

    public function testMultiPointEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('MULTIPOINT EMPTY');
    }

    // ---

    public function testMultiLineString()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createLineString')
            ->times(2)
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(5, 6), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(0, -3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(0, -4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, -5), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('MULTILINESTRING((1 2, 3 4, 5 6),(0 -3, 0 -4, 1 -5))');
    }

    public function testMultiLineStringEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('MULTILINESTRING EMPTY');
    }

    // ---

    public function testMultiPolygon()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiPolygon')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPolygon')
            ->times(2)
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createLinearRing')
            ->times(3)
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-1, -2, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-3, -4, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-5, -7, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-1, -2, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 0, -1), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(10, 0, -2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(10, 10, -3), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 10, -4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0, 0, -5), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 1, -6), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(2, 3, -7), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(3, 1, -8), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 1, -9), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('MULTIPOLYGON(((-1 -2 0, -3 -4 0, -5 -7 0, -1 -2 0)),((0 0 -1, 10 0 -2, 10 10 -3, 0 10 -4, 0 0 -5),(1 1 -6, 2 3 -7, 3 1 -8, 1 1 -9)))');
    }

    public function testMultiPolygonEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createMultiPolygon')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('MULTIPOLYGON EMPTY');
    }

    // ---

    public function testGeometryCollection()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createGeometryCollection')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(-1, -2), null)
        ;

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_2D, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1, 2), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(3, 4), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(5, 6), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION(POINT(-1 -2),LINESTRING(1 2, 3 4, 5 6))');
    }

    public function testGeometryCollectionZ()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createGeometryCollection')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-1, -2, 0), null)
        ;

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, Mockery::any(), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(1, 2, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(3, 4, 0), null)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(5, 6, 0), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION(POINT(-1 -2 0),LINESTRING(1 2 0, 3 4 0, 5 6 0))');
    }

    public function testGeometryCollectionEmpty()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createGeometryCollection')
            ->once()
            ->with(Dimension::DIMENSION_2D, array(), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION EMPTY');
    }

    public function testParseGeometryCollectionWithMixedZGeometries()
    {
        $geometries = <<<EOT
POINT (1 2 3),
POINT Z (4 5 6),
POINT Z EMPTY,

LINESTRING (1 2 3, 4 5 6),
LINESTRING Z (1 2 3, 4 5 6),
LINESTRING Z EMPTY,

POLYGON ((1 2 3, 4 5 6), (1 2 3, 4 5 6)),
POLYGON Z ((1 2 3, 4 5 6), (1 2 3, 4 5 6)),
POLYGON Z EMPTY,

MULTIPOINT ((1 2 4), (4 5 6)),
MULTIPOINT Z ((1 2 4), (4 5 6)),
MULTIPOINT (1 2 3, 4 5 6),
MULTIPOINT Z (1 2 3, 4 5 6),
MULTIPOINT Z EMPTY,

MULTILINESTRING ((1 2 3, 4 5 6), (1 2 3, 4 5 6)),
MULTILINESTRING Z ((1 2 3, 4 5 6), (1 2 3, 4 5 6)),
MULTILINESTRING Z EMPTY,

MULTIPOLYGON (((1 2 3, 4 5 6), (1 2 3, 4 5 6)), ((1 2 3, 4 5 6), (1 2 3, 4 5 6))),
MULTIPOLYGON Z (((1 2 3, 4 5 6), (1 2 3, 4 5 6)), ((1 2 3, 4 5 6), (1 2 3, 4 5 6))),
MULTIPOLYGON Z EMPTY
EOT;
        $createFactory = function () {
            $factory = Mockery::mock('GeoIO\\Factory');

            $factory
                ->shouldReceive('createGeometryCollection')
                ->times(1 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createPoint')
                ->times(47 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createLineString')
                ->times(7 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createLinearRing')
                ->times(12 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createPolygon')
                ->times(7 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createMultiPoint')
                ->times(5 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createMultiLineString')
                ->times(3 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            $factory
                ->shouldReceive('createMultiPolygon')
                ->times(3 * 2)
                ->with(Dimension::DIMENSION_3DZ, Mockery::any(), Mockery::any())
            ;

            return $factory;
        };

        // Without Z modifier
        $parser = new Parser($createFactory());
        $parser->parse('GEOMETRYCOLLECTION (' . $geometries . ', GEOMETRYCOLLECTION Z (' . $geometries . '))');

        // With Z modifier
        $parser = new Parser($createFactory());
        $parser->parse('GEOMETRYCOLLECTION Z (' . $geometries . ', GEOMETRYCOLLECTION (' . $geometries . '))');
    }

    // ---

    public function testCoordsFractional()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1.0, 2.5), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1.000 2.5)');
    }

    public function testCoordsFractional2()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_2D, $this->coords(1.0, 0.5), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1. .5)');
    }

    public function testCoordsNegative()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(-1.0, -0.5, -5.5), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(-1. -.5 -5.5)');
    }

    public function testCoordsScientificNumericValues()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createPoint')
            ->once()
            ->with(Dimension::DIMENSION_3DZ, $this->coords(0.00001, -0.0000000002, 3.45), null)
        ;

        $parser = new Parser($factory);
        $parser->parse('POINT(1.e-005 -.2e-009 3.45e-0)');
    }

    public function testParseIsCaseInsensitive()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createGeometryCollection')
            ->once()
            ->with(Dimension::DIMENSION_4D, Mockery::any(), 1234)
        ;

        $factory
            ->shouldReceive('createLineString')
            ->once()
            ->with(Dimension::DIMENSION_4D, Mockery::any(), 1234)
        ;

        $factory
            ->shouldReceive('createPoint')
            ->times(3)
            ->with(Dimension::DIMENSION_4D, Mockery::any(), 1234)
        ;

        $parser = new Parser($factory);
        $parser->parse("sRiD=1234;gEOmetryCollECTION zM(LINESTRING zm(7.120068 43.583917 1 2, 7.120154 43.583652 1 2), point Zm (7.120385 43.582716 1 2))");
    }

    public function testParseIgnoresWhitespace()
    {
        $factory = Mockery::mock('GeoIO\\Factory');

        $factory
            ->shouldReceive('createLineString')
            ->once()
        ;

        $factory
            ->shouldReceive('createPoint')
            ->times(6)
        ;

        $parser = new Parser($factory);
        $parser->parse("LINESTRING(7.120068\t43.583917,\n7.120154 43.583652,\n7.120385\t43.582716,\r\n7.12039 43.582568, 7.120712     43.581511,7.120873\n43.580718)");
    }

    public function testParseThrowsExceptionForNotEnough4DCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse('POINT ZM(1 2 3)');
    }

    public function testParseThrowsExceptionForNotEnough3DMCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse('POINT M(1 2)');
    }

    public function testParseThrowsExceptionForTooMany3DMCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse('POINT M(1 2 3 4)');
    }

    public function testParseThrowsExceptionForNotEnough3DZCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse('POINT Z(1 2)');
    }

    public function testParseThrowsExceptionForTooMany3DZCoords()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse('POINT Z(1 2 3 4)');
    }

    public function testParseThrowsExceptionForInvalidDataType()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());

        $parser->parse(1);
    }

    public function testParseThrowsExceptionForEmptyString()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('');
    }

    public function testParseThrowsExceptionForInvalidString()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('FOO');
    }

    public function testParseThrowsExceptionForUndefinedType()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('(1 2)');
    }

    public function testParseThrowsExceptionForInvalidType()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('InvalidGeometry(1 2)');
    }

    public function testParseThrowsExceptionForInvalidTypeInGeometryCollection()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('GeometryCollection(InvalidGeometry(1 2))');
    }

    public function testParseThrowsExceptionForMixedDimensionality()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('GeometryCollection (POINT (1 2 3 4), POINT (1 2 3))');
    }

    public function testParseThrowsExceptionForInvalidSRID()
    {
        $this->setExpectedException('GeoIO\\WKT\\Parser\\Exception\\ParserException');

        $parser = new Parser(Mockery::mock('GeoIO\\Factory')->shouldIgnoreMissing());
        $parser->parse('SRID=1.2;POINT(1 2)');
    }
}
