<?php

declare(strict_types=1);

namespace App\Command\Model;

use App\Command\Traits\ImportModelTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImportAttackModel extends AbstractModel
{
    use ImportModelTrait;

    public const COLUMN_ATTACK_NAME = 'name';
    public const COLUMN_ATTACK_TYPE = 'type';
    public const COLUMN_ATTACK_PHYSICAL = 'physical';
    public const COLUMN_ATTACK_POWER = 'power';
    public const COLUMN_ATTACK_ACCURACY = 'accuracy';
    public const COLUMN_ATTACK_PP = 'pp';
    public const COLUMN_ATTACK_DESCRIPTION = 'description';

    public static function buildOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver
            ->define(self::COLUMN_ATTACK_NAME)
            ->required()
            ->allowedTypes('string');
        self::defineTypeColumn($resolver, self::COLUMN_ATTACK_TYPE, nullable: false);
        $resolver
            ->define(self::COLUMN_ATTACK_PHYSICAL)
            ->required()
            ->allowedTypes('string')
            ->allowedValues('Physical', 'Special', 'Status')
        ;
        self::defineIntegerColumns([
            self::COLUMN_ATTACK_POWER,
            self::COLUMN_ATTACK_ACCURACY,
            self::COLUMN_ATTACK_PP,
        ], $resolver);
        $resolver
            ->define(self::COLUMN_ATTACK_DESCRIPTION)
            ->allowedTypes('string', 'null');

        return $resolver;
    }
}
