<?php

declare(strict_types=1);

namespace App\Exception;

final class UnexpectedValueException extends \RuntimeException
{
    public function __construct(mixed $value, ?\Throwable $previous = null)
    {
        if (\is_array($value) || \is_object($value)) {
            $givenValue = get_debug_type($value);
        } else {
            try {
                $givenValue = $value . ' (' . get_debug_type($value) . ')';
            } catch (\Throwable $throwable) {
                $givenValue = get_debug_type($value) . ' (error: ' . $throwable->getMessage() . ') ';
            }
        }

        parent::__construct(
            'Given value "' . $givenValue . '" was not expected.',
            500,
            $previous
        );
    }
}
