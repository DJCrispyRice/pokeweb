<?php

declare(strict_types=1);

namespace App\ValueObject;

final class TypesValueObject extends AbstractValueObject
{
    public const TYPES = [
        self::TYPE_NORMAL,
        self::TYPE_FIGHTING,
        self::TYPE_FLYING,
        self::TYPE_POISON,
        self::TYPE_GROUND,
        self::TYPE_ROCK,
        self::TYPE_BUG,
        self::TYPE_GHOST,
        self::TYPE_FIRE,
        self::TYPE_WATER,
        self::TYPE_GRASS,
        self::TYPE_ELECTRIC,
        self::TYPE_PSYCHIC,
        self::TYPE_ICE,
        self::TYPE_DRAGON,
    ];
    public const TYPE_NORMAL = 'Normal';
    public const TYPE_FIGHTING = 'Fighting';
    public const TYPE_FLYING = 'Flying';
    public const TYPE_POISON = 'Poison';
    public const TYPE_GROUND = 'Ground';
    public const TYPE_ROCK = 'Rock';
    public const TYPE_BUG = 'Bug';
    public const TYPE_GHOST = 'Ghost';
    public const TYPE_FIRE = 'Fire';
    public const TYPE_WATER = 'Water';
    public const TYPE_GRASS = 'Grass';
    public const TYPE_ELECTRIC = 'Electric';
    public const TYPE_PSYCHIC = 'Psychic';
    public const TYPE_ICE = 'Ice';
    public const TYPE_DRAGON = 'Dragon';
}
