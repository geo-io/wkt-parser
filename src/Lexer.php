<?php

namespace GeoIO\WKT\Parser;

use JMS\Parser\AbstractLexer;

class Lexer extends AbstractLexer
{
    public function getName($type)
    {
        return $type;
    }

    protected function getRegex()
    {
        return '/
            # Numbers
            ([-+]?[0-9]*\.?[0-9]*)

            |(SRID|POINT|LINESTRING|POLYGON|MULTIPOINT|MULTILINESTRING|MULTIPOLYGON|GEOMETRYCOLLECTION|ZM|M|Z|EMPTY)

            |(=)
            |(;)
            |(\()
            |(\))
            |(,)

            # Skip whitespaces
            |\s+
        /ix';
    }

    protected function determineTypeAndValue($value)
    {
        if (is_numeric($value)) {
            if (false !== strpos($value, '.') || false !== stripos($value, 'e')) {
                return array(
                    'FLOAT',
                    (float) $value
                );
            }

            return array(
                'INTEGER',
                (integer) $value
            );
        }

        $value = strtoupper($value);

        switch ($value) {
            case 'SRID':
            case '=':
            case ';':
            case 'POINT':
            case 'LINESTRING':
            case 'POLYGON':
            case 'MULTIPOINT':
            case 'MULTILINESTRING':
            case 'MULTIPOLYGON':
            case 'GEOMETRYCOLLECTION':
            case 'ZM':
            case 'M':
            case 'Z':
            case 'EMPTY':
            case '(':
            case ')':
            case ',':
                return array(
                    $value,
                    $value
                );
            default:
                return array(
                    'UNKNOWN',
                    $value
                );
        }
    }
}
