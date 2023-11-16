<?php

declare(strict_types=1);

namespace App\Exception;

final class InvalidEncodingException extends \RuntimeException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            'Encodage non supporté. Encodages supportés : "ASCII", "ISO-8859-1", "UTF-8".',
            400,
            $previous
        );
    }
}
