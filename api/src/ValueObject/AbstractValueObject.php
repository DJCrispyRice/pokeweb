<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\NotInstantiableException;

abstract class AbstractValueObject
{
    private function __construct()
    {
        throw new NotInstantiableException();
    }
}
