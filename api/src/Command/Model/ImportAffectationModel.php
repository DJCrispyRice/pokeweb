<?php

declare(strict_types=1);

namespace App\Command\Model;

use App\Command\Traits\ImportModelTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImportAffectationModel extends AbstractModel
{
    use ImportModelTrait;

    public const COLUMN_AFFECTATION_POKEMON = 'pokemon';
    public const COLUMN_AFFECTATION_ATTACK_1 = 'attack_1';
    public const COLUMN_AFFECTATION_ATTACK_2 = 'attack_2';
    public const COLUMN_AFFECTATION_ATTACK_3 = 'attack_3';
    public const COLUMN_AFFECTATION_ATTACK_4 = 'attack_4';

    public static function buildOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        self::defineStringColumns($resolver, [
            self::COLUMN_AFFECTATION_POKEMON,
            self::COLUMN_AFFECTATION_ATTACK_1,
        ], nullable: false);

        self::defineStringColumns($resolver, [
            self::COLUMN_AFFECTATION_ATTACK_2,
            self::COLUMN_AFFECTATION_ATTACK_3,
            self::COLUMN_AFFECTATION_ATTACK_4,
        ], nullable: true);

        return $resolver;
    }
}
