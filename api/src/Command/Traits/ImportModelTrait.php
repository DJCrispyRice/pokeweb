<?php

declare(strict_types=1);

namespace App\Command\Traits;

use App\ValueObject\TypesValueObject;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait ImportModelTrait
{
    private static function defineIntegerColumns(array $columns, OptionsResolver $resolver): void
    {
        foreach (
            $columns as $column
        ) {
            $resolver
                ->define($column)
                ->required()
                ->allowedTypes('string')
                ->allowedValues(
                    static fn (string $value) => is_numeric($value)
                )
                ->normalize(
                    static fn (Options $options, string $value) => (int) $value
                );
        }
    }

    private static function defineTypeColumn(OptionsResolver $resolver, string $column, bool $nullable): void
    {
        $resolver
            ->define($column)
            ->allowedTypes('string', $nullable === true ? 'null' : '')
            ->allowedValues(
                static function (?string $value) {
                    if ($value === null) {
                        return true;
                    }

                    return \in_array(
                        ucfirst($value),
                        TypesValueObject::TYPES,
                        true
                    );
                }
            );
    }
}
