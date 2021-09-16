<?php

declare(strict_types=1);

namespace GeoIO\WKT\Parser;

use GeoIO\Coordinates;
use GeoIO\Dimension;
use GeoIO\Factory;
use GeoIO\WKT\Parser\Exception\ParserException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private function coords($x, $y, $z = null, $m = null): Coordinates
    {
        return new Coordinates(
            x: $x,
            y: $y,
            z: $z,
            m: $m,
        );
    }

    public function testPoint(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_2D, null, $this->coords(1, 2));

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2)');
    }

    public function testPointEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_2D, null, null);

        $parser = new Parser($factory);
        $parser->parse('POINT EMPTY');
    }

    public function testPoint3Coords(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_3DZ, null, $this->coords(1, 2, 3));

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2 3)');
    }

    public function testPointWith4Coords(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_4D, null, $this->coords(1, 2, 3, 4));

        $parser = new Parser($factory);
        $parser->parse('POINT(1 2 3 4)');
    }

    public function testPointZ(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_3DZ, null, $this->coords(1, 2, 3));

        $parser = new Parser($factory);
        $parser->parse('POINT Z(1 2 3)');
    }

    public function testPointM(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_3DM, null, $this->coords(1, 2, null, 3));

        $parser = new Parser($factory);
        $parser->parse('POINT M(1 2 3)');
    }

    public function testPointZM(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_4D, null, $this->coords(1, 2, 3, 4));

        $parser = new Parser($factory);
        $parser->parse('POINT ZM(1 2 3 4)');
    }

    public function testPointWithSrid(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_2D, 1234, $this->coords(1, 2));

        $parser = new Parser($factory);
        $parser->parse('SRID=1234;POINT(1 2)');
    }

    // ---

    public function testLineString(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(3))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(3, 4)],
                [Dimension::DIMENSION_2D, null, $this->coords(5, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2, 3 4, 5 6)');
    }

    public function testLineStringEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('LINESTRING EMPTY');
    }

    public function testLineString3Coords(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 2, 3)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(4, 5, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3, 4 5 6)');
    }

    public function testLineStringWith4Coords(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_4D, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_4D, null, $this->coords(1, 2, 3, 4)],
                [Dimension::DIMENSION_4D, null, $this->coords(5, 6, 7, 8)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3 4, 5 6 7 8)');
    }

    public function testLineStringZ(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 2, 3)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(4, 5, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING Z(1 2 3, 4 5 6)');
    }

    public function testLineStringM(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_3DM, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DM, null, $this->coords(1, 2, null, 3)],
                [Dimension::DIMENSION_3DM, null, $this->coords(4, 5, null, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING M(1 2 3, 4 5 6)');
    }

    public function testLineStringZM(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_4D, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_4D, null, $this->coords(1, 2, 3, 4)],
                [Dimension::DIMENSION_4D, null, $this->coords(5, 6, 7, 8)],
            );

        $parser = new Parser($factory);
        $parser->parse('LINESTRING ZM(1 2 3 4, 5 6 7 8)');
    }

    public function testLineStringWithSrid(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_2D, 1234, $this->anything());

        $factory
            ->expects($this->exactly(3))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, 1234, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, 1234, $this->coords(3, 4)],
                [Dimension::DIMENSION_2D, 1234, $this->coords(5, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('SRID=1234;LINESTRING(1 2, 3 4, 5 6)');
    }

    public function testLineStringWithInconsistentCoords(): void
    {
        $factory = $this->createMock(Factory::class);

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of FLOAT or INTEGER, but got "," at position 21 (0-based).');

        $parser = new Parser($factory);
        $parser->parse('LINESTRING(1 2 3, 4 5,7 8 9)');
    }

    // ---

    public function testPolygon(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPolygon')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->once())
            ->method('createLinearRing')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(4))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(3, 4)],
                [Dimension::DIMENSION_2D, null, $this->coords(5, 6)],
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
            );

        $parser = new Parser($factory);
        $parser->parse('POLYGON((1 2, 3 4, 5 6, 1 2))');
    }

    public function testPolygonEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPolygon')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('POLYGON EMPTY');
    }

    public function testPolygonWithHoles(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPolygon')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createLinearRing')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->anything()],
                [Dimension::DIMENSION_3DZ, null, $this->anything()],
            );

        $factory
            ->expects($this->exactly(9))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 0, -1)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(10, 0, -2)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(10, 10, -3)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 10, -4)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 0, -5)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 1, -6)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(2, 3, -7)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(3, 1, -8)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 1, -9)],
            );

        $parser = new Parser($factory);
        $parser->parse('POLYGON((0 0 -1, 10 0 -2, 10 10 -3, 0 10 -4, 0 0 -5),(1 1 -6, 2 3 -7, 3 1 -8, 1 1 -9))');
    }

    // ---

    public function testParseMultiPoint(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiPoint')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(0, 3)],
            );

        $parser = new Parser($factory);
        $parser->parse('MULTIPOINT((1 2), (0 3))');
    }

    public function testParseMultiPointNonStandard(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiPoint')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(0, 3)],
            );

        $parser = new Parser($factory);
        $parser->parse('MULTIPOINT(1 2, 0 3)');
    }

    public function testMultiPointEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiPoint')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('MULTIPOINT EMPTY');
    }

    // ---

    public function testMultiLineString(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiLineString')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createLineString')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(6))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(3, 4)],
                [Dimension::DIMENSION_2D, null, $this->coords(5, 6)],
                [Dimension::DIMENSION_2D, null, $this->coords(0, -3)],
                [Dimension::DIMENSION_2D, null, $this->coords(0, -4)],
                [Dimension::DIMENSION_2D, null, $this->coords(1, -5)],
            );

        $parser = new Parser($factory);
        $parser->parse('MULTILINESTRING((1 2, 3 4, 5 6),(0 -3, 0 -4, 1 -5))');
    }

    public function testMultiLineStringEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiLineString')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('MULTILINESTRING EMPTY');
    }

    // ---

    public function testMultiPolygon(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiPolygon')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(2))
            ->method('createPolygon')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(3))
            ->method('createLinearRing')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(13))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->coords(-1, -2, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(-3, -4, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(-5, -7, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(-1, -2, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 0, -1)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(10, 0, -2)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(10, 10, -3)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 10, -4)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(0, 0, -5)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 1, -6)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(2, 3, -7)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(3, 1, -8)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 1, -9)],
            );

        $parser = new Parser($factory);
        $parser->parse('MULTIPOLYGON(((-1 -2 0, -3 -4 0, -5 -7 0, -1 -2 0)),((0 0 -1, 10 0 -2, 10 10 -3, 0 10 -4, 0 0 -5),(1 1 -6, 2 3 -7, 3 1 -8, 1 1 -9)))');
    }

    public function testMultiPolygonEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createMultiPolygon')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('MULTIPOLYGON EMPTY');
    }

    // ---

    public function testGeometryCollection(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createGeometryCollection')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_2D, null, $this->anything());

        $factory
            ->expects($this->exactly(4))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_2D, null, $this->coords(-1, -2)],
                [Dimension::DIMENSION_2D, null, $this->coords(1, 2)],
                [Dimension::DIMENSION_2D, null, $this->coords(3, 4)],
                [Dimension::DIMENSION_2D, null, $this->coords(5, 6)],
            );

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION(POINT(-1 -2),LINESTRING(1 2, 3 4, 5 6))');
    }

    public function testGeometryCollectionZ(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createGeometryCollection')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_3DZ, null, $this->anything());

        $factory
            ->expects($this->exactly(4))
            ->method('createPoint')
            ->withConsecutive(
                [Dimension::DIMENSION_3DZ, null, $this->coords(-1, -2, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(1, 2, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(3, 4, 0)],
                [Dimension::DIMENSION_3DZ, null, $this->coords(5, 6, 0)],
            );

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION(POINT(-1 -2 0),LINESTRING(1 2 0, 3 4 0, 5 6 0))');
    }

    public function testGeometryCollectionEmpty(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createGeometryCollection')
            ->with(Dimension::DIMENSION_2D, null, []);

        $parser = new Parser($factory);
        $parser->parse('GEOMETRYCOLLECTION EMPTY');
    }

    public function testParseGeometryCollectionWithMixedZGeometries(): void
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
            $factory = $this->createMock(Factory::class);

            $factory
                ->expects($this->exactly(1 * 2))
                ->method('createGeometryCollection')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(47 * 2))
                ->method('createPoint')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(7 * 2))
                ->method('createLineString')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(12 * 2))
                ->method('createLinearRing')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(7 * 2))
                ->method('createPolygon')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(5 * 2))
                ->method('createMultiPoint')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(3 * 2))
                ->method('createMultiLineString')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

            $factory
                ->expects($this->exactly(3 * 2))
                ->method('createMultiPolygon')
                ->with(Dimension::DIMENSION_3DZ, $this->anything(), $this->anything());

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

    public function testCoordsFractional(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_2D, null, $this->coords(1.0, 2.5));

        $parser = new Parser($factory);
        $parser->parse('POINT(1.000 2.5)');
    }

    public function testCoordsFractional2(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_2D, null, $this->coords(1.0, 0.5));

        $parser = new Parser($factory);
        $parser->parse('POINT(1. .5)');
    }

    public function testCoordsNegative(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_3DZ, null, $this->coords(-1.0, -0.5, -5.5));

        $parser = new Parser($factory);
        $parser->parse('POINT(-1. -.5 -5.5)');
    }

    public function testCoordsScientificNumericValues(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createPoint')
            ->with(Dimension::DIMENSION_3DZ, null, $this->coords(0.00001, -0.0000000002, 3.45));

        $parser = new Parser($factory);
        $parser->parse('POINT(1.e-005 -.2e-009 3.45e-0)');
    }

    public function testParseIsCaseInsensitive(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createGeometryCollection')
            ->with(Dimension::DIMENSION_4D, 1234, $this->anything());

        $factory
            ->expects($this->once())
            ->method('createLineString')
            ->with(Dimension::DIMENSION_4D, 1234, $this->anything());

        $factory
            ->expects($this->exactly(3))
            ->method('createPoint')
            ->with(Dimension::DIMENSION_4D, 1234, $this->anything());

        $parser = new Parser($factory);
        $parser->parse('sRiD=1234;gEOmetryCollECTION zM(LINESTRING zm(7.120068 43.583917 1 2, 7.120154 43.583652 1 2), point Zm (7.120385 43.582716 1 2))');
    }

    public function testParseIgnoresWhitespace(): void
    {
        $factory = $this->createMock(Factory::class);

        $factory
            ->expects($this->once())
            ->method('createLineString');

        $factory
            ->expects($this->exactly(6))
            ->method('createPoint');

        $parser = new Parser($factory);
        $parser->parse("LINESTRING(7.120068\t43.583917,\n7.120154 43.583652,\n7.120385\t43.582716,\r\n7.12039 43.582568, 7.120712     43.581511,7.120873\n43.580718)");
    }

    public function testParseThrowsExceptionForNotEnough4DCoords(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of FLOAT or INTEGER, but got ")" at position 14 (0-based).');

        $parser->parse('POINT ZM(1 2 3)');
    }

    public function testParseThrowsExceptionForNotEnough3DMCoords(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of FLOAT or INTEGER, but got ")" at position 11 (0-based).');

        $parser->parse('POINT M(1 2)');
    }

    public function testParseThrowsExceptionForTooMany3DMCoords(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected ), but got "4" at position 14 (0-based).');

        $parser->parse('POINT M(1 2 3 4)');
    }

    public function testParseThrowsExceptionForNotEnough3DZCoords(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of FLOAT or INTEGER, but got ")" at position 11 (0-based).');

        $parser->parse('POINT Z(1 2)');
    }

    public function testParseThrowsExceptionForTooMany3DZCoords(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected ), but got "4" at position 14 (0-based).');

        $parser->parse('POINT Z(1 2 3 4)');
    }

    public function testParseThrowsExceptionForEmptyString(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of POINT or LINESTRING or POLYGON or MULTIPOINT or MULTILINESTRING or MULTIPOLYGON or GEOMETRYCOLLECTION, but got end of input.');

        $parser->parse('');
    }

    public function testParseThrowsExceptionForInvalidString(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of POINT or LINESTRING or POLYGON or MULTIPOINT or MULTILINESTRING or MULTIPOLYGON or GEOMETRYCOLLECTION, but got "F" at beginning of input.');

        $parser->parse('FOO');
    }

    public function testParseThrowsExceptionForSuperfluousCharacter(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected end of input, but got ")" at position 10 (0-based).');

        $parser->parse('POINT(1 2))');
    }

    public function testParseThrowsExceptionForUndefinedType(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of POINT or LINESTRING or POLYGON or MULTIPOINT or MULTILINESTRING or MULTIPOLYGON or GEOMETRYCOLLECTION, but got "(" at beginning of input.');

        $parser->parse('(1 2)');
    }

    public function testParseThrowsExceptionForInvalidType(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of POINT or LINESTRING or POLYGON or MULTIPOINT or MULTILINESTRING or MULTIPOLYGON or GEOMETRYCOLLECTION, but got "I" at beginning of input.');

        $parser->parse('InvalidGeometry(1 2)');
    }

    public function testParseThrowsExceptionForInvalidTypeInGeometryCollection(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of POINT or LINESTRING or POLYGON or MULTIPOINT or MULTILINESTRING or MULTIPOLYGON or GEOMETRYCOLLECTION, but got "I" at position 19 (0-based).');

        $parser->parse('GeometryCollection(InvalidGeometry(1 2))');
    }

    public function testParseThrowsExceptionForMixedDimensionality(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected any of FLOAT or INTEGER, but got ")" at position 49 (0-based).');

        $parser->parse('GeometryCollection (POINT (1 2 3 4), POINT (1 2 3))');
    }

    public function testParseThrowsExceptionForInvalidSRID(): void
    {
        $parser = new Parser($this->createMock(Factory::class));

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('Expected INTEGER, but got "1.2" at position 5 (0-based).');

        $parser->parse('SRID=1.2;POINT(1 2)');
    }
}
