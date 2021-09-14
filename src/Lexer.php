<?php

declare(strict_types=1);

namespace GeoIO\WKT\Parser;

use GeoIO\WKT\Parser\Exception\ParserException;

/**
 * @internal
 */
final class Lexer
{
    /**
     * @var array{0: int|float|string, 1: int, 2: string}|null
     */
    public ?array $token = null;

    /**
     * @var array{0: int|float|string, 1: int, 2: string}|null
     */
    public ?array $next = null;

    private int $i = -1;

    /**
     * @var array<array{0: int|float|string, 1: int, 2: string}>
     */
    private array $tokens;

    public function __construct(string $input)
    {
        $tokens = preg_split(
            '/
                # Numbers
                ([-+]?[\d]*\.?[\d]*e[-+]?[\d]+|[-+]?[\d]*\.?[\d]*)

                |(SRID|POINT|LINESTRING|POLYGON|MULTIPOINT|MULTILINESTRING|MULTIPOLYGON|GEOMETRYCOLLECTION|ZM|M|Z|EMPTY)

                |(=)
                |(;)
                |(\()
                |(\))
                |(,)

                # Skip whitespaces
                |\s+
            /ix',
            $input,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE
        );

        $this->tokens = [];

        foreach ($tokens as $token) {
            [$token[2], $token[0]] = $this->determineTypeAndValue($token[0]);
            $this->tokens[] = $token;
        }

        $this->moveNext();
    }

    public function match(string $type): int|float|string|null
    {
        if (!$this->isNext($type)) {
            throw ParserException::create(
                $type,
                $this->next,
            );
        }

        $this->moveNext();

        return $this->token[0] ?? null;
    }

    /**
     * @param array<string> $types
     */
    public function matchAny(array $types): int|float|string|null
    {
        if (!$this->isNextAny($types)) {
            throw ParserException::create(
                'any of ' . implode(' or ', $types),
                $this->next,
            );
        }

        $this->moveNext();

        return $this->token[0] ?? null;
    }

    public function isNext(string $type): bool
    {
        return null !== $this->next && $type === $this->next[2];
    }

    /**
     * @param array<string> $types
     */
    public function isNextAny(array $types): bool
    {
        if (null === $this->next) {
            return false;
        }

        foreach ($types as $type) {
            if ($type === $this->next[2]) {
                return true;
            }
        }

        return false;
    }

    private function moveNext(): void
    {
        $this->token = $this->next;
        $this->next = isset($this->tokens[++$this->i]) ? $this->tokens[$this->i] : null;
    }

    /**
     * @return array{0: string, 1: int|float|string}
     */
    private function determineTypeAndValue(string $value): array
    {
        if (is_numeric($value)) {
            if (str_contains($value, '.') || false !== stripos($value, 'e')) {
                return [
                    'FLOAT',
                    (float) $value,
                ];
            }

            return [
                'INTEGER',
                (int) $value,
            ];
        }

        $value = strtoupper($value);

        return match ($value) {
            'SRID',
            '=',
            ';',
            'POINT',
            'LINESTRING',
            'POLYGON',
            'MULTIPOINT',
            'MULTILINESTRING',
            'MULTIPOLYGON',
            'GEOMETRYCOLLECTION',
            'ZM',
            'M',
            'Z',
            'EMPTY',
            '(',
            ')',
            ',' => [
                $value,
                $value,
            ],
            default => [
                'UNKNOWN',
                $value,
            ],
        };
    }
}
