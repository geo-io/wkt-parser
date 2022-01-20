<?php

declare(strict_types=1);

namespace GeoIO\WKT\Parser\Exception;

use RuntimeException;

final class ParserException extends RuntimeException implements Exception
{
    /**
     * @param array{0: int|float|string, 1: int, 2: string}|null $actualToken
     */
    public static function create(
        string $expectedDesc,
        ?array $actualToken,
    ): self {
        if (null === $actualToken) {
            $actualDesc = 'end of input';
        } elseif (0 === $actualToken[1]) {
            $actualDesc = sprintf(
                '"%s" at beginning of input',
                $actualToken[0],
            );
        } else {
            $actualDesc = sprintf(
                '"%s" at position %d (0-based)',
                $actualToken[0],
                $actualToken[1],
            );
        }

        return new self(
            sprintf(
                'Expected %s, but got %s.',
                $expectedDesc,
                $actualDesc,
            ),
        );
    }
}
