<?php

declare(strict_types=1);

namespace GeoIO\WKT\Parser;

use GeoIO\Coordinates;
use GeoIO\Dimension;
use GeoIO\Factory;
use GeoIO\WKT\Parser\Exception\ParserException;

final class Parser
{
    private Factory $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function parse(string $input): mixed
    {
        $lexer = new Lexer($input);

        $geometry = $this->parseGeometry($lexer);

        if (null !== $lexer->next) {
            throw ParserException::create(
                'end of input',
                $lexer->next,
            );
        }

        return $geometry;
    }

    private function parseGeometry(Lexer $lexer): mixed
    {
        $srid = $this->srid($lexer);
        $dimension = null;

        return $this->geometry($lexer, $srid, $dimension);
    }

    private function srid(Lexer $lexer): ?int
    {
        $srid = null;

        if ($lexer->isNext('SRID')) {
            $lexer->match('SRID');
            $lexer->match('=');

            $srid = (int) $lexer->match('INTEGER');

            $lexer->match(';');
        }

        return $srid;
    }

    private function geometry(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $types = [
            'POINT',
            'LINESTRING',
            'POLYGON',
            'MULTIPOINT',
            'MULTILINESTRING',
            'MULTIPOLYGON',
            'GEOMETRYCOLLECTION',
        ];

        return match ($lexer->matchAny($types)) {
            'POINT' => $this->point(
                $lexer,
                $srid,
                $dimension,
            ),
            'LINESTRING' => $this->lineString(
                $lexer,
                $srid,
                $dimension,
            ),
            'POLYGON' => $this->polygon(
                $lexer,
                $srid,
                $dimension,
            ),
            'MULTIPOINT' => $this->multiPoint(
                $lexer,
                $srid,
                $dimension,
            ),
            'MULTILINESTRING' => $this->multiLineString(
                $lexer,
                $srid,
                $dimension,
            ),
            'MULTIPOLYGON' => $this->multiPolygon(
                $lexer,
                $srid,
                $dimension,
            ),
            default => $this->geometryCollection(
                $lexer,
                $srid,
                $dimension,
            ),
        };
    }

    private function dimension(
        Lexer $lexer,
        ?string $dimension,
    ): ?string {
        if (
            (Dimension::DIMENSION_4D === $dimension || null === $dimension) &&
            $lexer->isNext('ZM')
        ) {
            $lexer->match('ZM');

            return Dimension::DIMENSION_4D;
        }

        if (
            (Dimension::DIMENSION_3DM === $dimension || null === $dimension) &&
            $lexer->isNext('M')
        ) {
            $lexer->match('M');

            return Dimension::DIMENSION_3DM;
        }

        if (
            (Dimension::DIMENSION_3DZ === $dimension || null === $dimension) &&
            $lexer->isNext('Z')
        ) {
            $lexer->match('Z');

            return Dimension::DIMENSION_3DZ;
        }

        return $dimension;
    }

    private function coordinates(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $coordinates = [
            'x' => (float) $lexer->matchAny(['FLOAT', 'INTEGER']),
            'y' => (float) $lexer->matchAny(['FLOAT', 'INTEGER']),
            'z' => null,
            'm' => null,
        ];

        if (
            Dimension::DIMENSION_3DZ === $dimension ||
            Dimension::DIMENSION_4D === $dimension ||
            (null === $dimension && $lexer->isNextAny(['FLOAT', 'INTEGER']))
        ) {
            $coordinates['z'] = (float) $lexer->matchAny(['FLOAT', 'INTEGER']);
        }

        if (
            Dimension::DIMENSION_3DM === $dimension ||
            Dimension::DIMENSION_4D === $dimension ||
            (null === $dimension && $lexer->isNextAny(['FLOAT', 'INTEGER']))
        ) {
            $coordinates['m'] = (float) $lexer->matchAny(['FLOAT', 'INTEGER']);
        }

        if (null === $dimension) {
            if (isset($coordinates['z'], $coordinates['m'])) {
                $dimension = Dimension::DIMENSION_4D;
            } elseif (isset($coordinates['z'])) {
                $dimension = Dimension::DIMENSION_3DZ;
            }
        }

        return $this->factory->createPoint(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            new Coordinates(...$coordinates),
        );
    }

    private function point(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createPoint(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                null,
            );
        }

        $lexer->match('(');

        $point = $this->coordinates(
            $lexer,
            $srid,
            $dimension,
        );

        $lexer->match(')');

        return $point;
    }

    private function lineStringText(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
        bool $isLinearRing = false,
    ): mixed {
        $lexer->match('(');
        $points = [];

        while (true) {
            $points[] = $this->coordinates(
                $lexer,
                $srid,
                $dimension,
            );

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        if ($isLinearRing) {
            return $this->factory->createLinearRing(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                $points,
            );
        }

        return $this->factory->createLineString(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $points,
        );
    }

    private function lineString(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createLineString(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        return $this->lineStringText(
            $lexer,
            $srid,
            $dimension,
        );
    }

    private function polygonText(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $lexer->match('(');

        $lineStrings = [];

        while (true) {
            $lineStrings[] = $this->lineStringText(
                $lexer,
                $srid,
                $dimension,
                true,
            );

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        return $this->factory->createPolygon(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $lineStrings,
        );
    }

    private function polygon(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createPolygon(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        return $this->polygonText(
            $lexer,
            $srid,
            $dimension,
        );
    }

    private function multiPoint(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createMultiPoint(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        $lexer->match('(');

        $points = [];

        while (true) {
            $nonStandardPoint = true;

            if ($lexer->isNext('(')) {
                $lexer->match('(');
                $nonStandardPoint = false;
            }

            $points[] = $this->coordinates(
                $lexer,
                $srid,
                $dimension,
            );

            if (!$nonStandardPoint) {
                $lexer->match(')');
            }

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        return $this->factory->createMultiPoint(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $points,
        );
    }

    private function multiLineString(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createMultiLineString(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        $lexer->match('(');

        $lineStrings = [];

        while (true) {
            $lineStrings[] = $this->lineStringText(
                $lexer,
                $srid,
                $dimension,
            );

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        return $this->factory->createMultiLineString(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $lineStrings,
        );
    }

    private function multiPolygon(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension,
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createMultiPolygon(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        $lexer->match('(');

        $polygons = [];

        while (true) {
            $polygons[] = $this->polygonText(
                $lexer,
                $srid,
                $dimension,
            );

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        return $this->factory->createMultiPolygon(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $polygons,
        );
    }

    private function geometryCollection(
        Lexer $lexer,
        ?int $srid,
        ?string &$dimension
    ): mixed {
        $dimension = $this->dimension(
            $lexer,
            $dimension,
        );

        if ($lexer->isNext('EMPTY')) {
            $lexer->match('EMPTY');

            return $this->factory->createGeometryCollection(
                $dimension ?: Dimension::DIMENSION_2D,
                $srid,
                [],
            );
        }

        $lexer->match('(');

        $geometries = [];

        while (true) {
            $geometry = $this->geometry(
                $lexer,
                $srid,
                $dimension,
            );

            $geometries[] = $geometry;

            if (!$lexer->isNext(',')) {
                break;
            }

            $lexer->match(',');
        }

        $lexer->match(')');

        return $this->factory->createGeometryCollection(
            $dimension ?: Dimension::DIMENSION_2D,
            $srid,
            $geometries,
        );
    }
}
