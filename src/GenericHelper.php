<?php

namespace Mpyw\OpenGraph;

use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

/**
 * Trait GenericHelper
 */
trait GenericHelper
{
    /**
     * @param  string                 $value
     * @return null|DateTimeInterface
     */
    protected function convertToDateTime(string $value): ?DateTimeInterface
    {
        try {
            return new DateTimeImmutable($value);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * @param  string $value
     * @return bool
     */
    protected function convertToBoolean(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
