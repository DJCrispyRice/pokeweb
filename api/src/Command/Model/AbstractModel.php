<?php

declare(strict_types=1);

namespace App\Command\Model;

use App\Exception\NotInstantiableException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractModel
{
    private function __construct()
    {
        throw new NotInstantiableException();
    }

    abstract public static function buildOptionsResolver(): OptionsResolver;
}
