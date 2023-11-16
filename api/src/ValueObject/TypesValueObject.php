<?php

declare(strict_types=1);

namespace App\ValueObject;

class TypesValueObject extends AbstractValueObject
{
    public const TYPES = [
        'Normal',
        'Fighting',
        'Flying',
        'Poison',
        'Ground',
        'Rock',
        'Bug',
        'Ghost',
        'Fire',
        'Water',
        'Grass',
        'Electric',
        'Psychic',
        'Ice',
        'Dragon',
    ];
}
