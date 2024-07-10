<?php

declare(strict_types=1);

namespace App\Command\Model;

use App\Command\Traits\ImportModelTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImportPokemonModel extends AbstractModel
{
    use ImportModelTrait;

    public const COLUMN_POKEMON_NUMBER = 'num';
    public const COLUMN_POKEMON_NAME = 'name';
    public const COLUMN_POKEMON_HP = 'hp';
    public const COLUMN_POKEMON_ATK = 'atk';
    public const COLUMN_POKEMON_DEF = 'def';
    public const COLUMN_POKEMON_SPD = 'spd';
    public const COLUMN_POKEMON_SPE = 'spe';
    public const COLUMN_POKEMON_TYPE_1 = 'type_1';
    public const COLUMN_POKEMON_TYPE_2 = 'type_2';

    public static function buildOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        self::defineStringColumns($resolver, [
            self::COLUMN_POKEMON_NAME,
        ], nullable: false);

        self::defineIntegerColumns([
            self::COLUMN_POKEMON_NUMBER,
            self::COLUMN_POKEMON_HP,
            self::COLUMN_POKEMON_ATK,
            self::COLUMN_POKEMON_DEF,
            self::COLUMN_POKEMON_SPD,
            self::COLUMN_POKEMON_SPE,
        ], $resolver);

        self::defineTypeColumn($resolver, self::COLUMN_POKEMON_TYPE_1, nullable: false);

        self::defineTypeColumn($resolver, self::COLUMN_POKEMON_TYPE_2, nullable: true);

        return $resolver;
    }
}
