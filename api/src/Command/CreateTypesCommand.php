<?php

declare(strict_types=1);

namespace App\Command;

use App\Autowire\Dependencies\Doctrine\ORM\AutowireEntityManagerInterfaceTrait;
use App\Entity\Type;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-types')]
final class CreateTypesCommand extends Command
{
    use AutowireEntityManagerInterfaceTrait;

    protected function configure(): void
    {
        $this
            ->setHelp('Creates types with the table of strengh/weakness.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Creating types...');
        $progressBar = new ProgressBar($output, count($this->getAllTypes()));
        $progressBar->start();
        $types = [];
        foreach ($this->getAllTypes() as $type) {
            $types[$type] = (new Type())->setLabel($type);
            $progressBar->advance();
        }
        $progressBar->finish();
        $io->newLine();
        $io->success('Types created.');
        $io->info('Creating the table of strengh/weakness...');
        $progressBar = new ProgressBar($output, count($this->getAllTypes()));
        $progressBar->start();
        foreach ($types as $type) {
            $tableOfStrength = $this->getStrengthWeaknesses()[$type->getLabel()];
            if (array_key_exists('strength', $tableOfStrength)) {
                foreach ($tableOfStrength['strength'] as $strength) {
                    $type->addStrength($types[$strength]);
                }
            }
            if (array_key_exists('weakness', $tableOfStrength)) {
                foreach ($tableOfStrength['weakness'] as $strength) {
                    $type->addWeakness($types[$strength]);
                }
            }
            if (array_key_exists('useless', $tableOfStrength)) {
                foreach ($tableOfStrength['useless'] as $strength) {
                    $type->addUseless($types[$strength]);
                }
            }
            $this->getEntityManager()->persist($type);
            $progressBar->advance();
        }
        $io->newLine();
        $io->success('Table of strength/weakness created.');
        $this->getEntityManager()->flush();
        $progressBar->finish();
        $io->newLine();

        return Command::SUCCESS;
    }

    private function getAllTypes(): array
    {
        return [
            'Normal',
            'Fight',
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

    private function getStrengthWeaknesses(): array
    {
        return [
            'Normal' => [
                'weakness' => [
                    'Rock',
                ],
                'useless' => [
                    'Ghost',
                ],
            ],

            'Fight' => [
                'strength' => [
                    'Normal',
                    'Rock',
                ],
                'weakness' => [
                    'Flying',
                    'Poison',
                    'Bug',
                    'Psychic',
                ],
            ],

            'Flying' => [
                'strength' => [
                    'Fight',
                    'Bug',
                    'Grass',
                ],
                'weakness' => [
                    'Rock',
                    'Electric',
                ],
            ],

            'Poison' => [
                'strength' => [
                    'Grass',
                ],
                'weakness' => [
                    'Poison',
                    'Rock',
                    'Ground',
                    'Ghost',
                ],
            ],

            'Ground' => [
                'strength' => [
                    'Poison',
                    'Rock',
                    'Fire',
                    'Electric',
                ],
                'weakness' => [
                    'Bug',
                ],
                'useless' => [
                    'Flying',
                ],
            ],

            'Rock' => [
                'strength' => [
                    'Flying',
                    'Bug',
                    'Fire',
                    'Ice',
                ],
                'weakness' => [
                    'Fight',
                    'Ground',
                ],
            ],

            'Bug' => [
                'strength' => [
                    'Grass',
                    'Poison',
                    'Psychic',
                ],
                'weakness' => [
                    'Fight',
                    'Flying',
                    'Ghost',
                    'Fire',
                ],
            ],

            'Ghost' => [
                'strength' => [
                    'Ghost',
                    'Psychic',
                ],
                'useless' => [
                    'Normal',
                ],
            ],

            'Fire' => [
                'strength' => [
                    'Bug',
                    'Grass',
                    'Ice',
                ],
                'weakness' => [
                    'Water',
                    'Grass',
                    'Dragon',
                ],
            ],

            'Water' => [
                'strength' => [
                    'Ground',
                    'Rock',
                    'Fire',
                ],
                'weakness' => [
                    'Water',
                    'Grass',
                    'Dragon',
                ],
            ],

            'Grass' => [
                'strength' => [
                    'Ground',
                    'Rock',
                    'Water',
                ],
                'weakness' => [
                    'Flying',
                    'Poison',
                    'Bug',
                    'Fire',
                    'Grass',
                    'Dragon',
                ],
            ],

            'Electric' => [
                'strength' => [
                    'Flying',
                    'Water',
                ],
                'weakness' => [
                    'Grass',
                    'Rock',
                    'Electric',
                    'Dragon',
                ],
                'useless' => [
                    'Ground',
                ],
            ],

            'Psychic' => [
                'strength' => [
                    'Fight',
                    'Poison',
                ],
                'weakness' => [
                    'Psychic',
                ],
            ],

            'Ice' => [
                'strength' => [
                    'Flying',
                    'Ground',
                    'Grass',
                    'Dragon',
                ],
                'weakness' => [
                    'Water',
                    'Ice',
                ],
            ],

            'Dragon' => [
                'strength' => [
                    'Dragon',
                ],
            ],
        ];
    }
}
