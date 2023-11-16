<?php

declare(strict_types=1);

namespace App\Exception;

final class NotInstantiableException extends \RuntimeException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('This cannot be instanced and shouldn\'n be possible', previous: $previous);
    }
}
