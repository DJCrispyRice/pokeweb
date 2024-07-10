<?php

declare(strict_types=1);

namespace App\Command;

use App\Autowire\Dependencies\Doctrine\ORM\AutowireEntityManagerInterfaceTrait;
use App\Autowire\Repository\AutowireTypeRepositoryTrait;
use App\Command\Model\ImportPokemonModel;
use App\Command\Traits\ImportTrait;
use App\Entity\Pokemon;
use App\Util\Import\ImportUtil;
use App\Util\String\StringUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-pokemons')]
final class CreatePokemonsCommand extends Command
{
    use AutowireEntityManagerInterfaceTrait;
    use AutowireTypeRepositoryTrait;
    use ImportTrait;

    private const FILE = 'pkmn.csv';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Creating pokémons...');
        $resolver = ImportPokemonModel::buildOptionsResolver();
        $file = $this->openCsvFile(self::FILE);
        if ($file === null) {
            $io->error('The pokémon base file was not found. Exiting');

            return Command::FAILURE;
        }
        $progressBar = new ProgressBar($output, ImportUtil::countRow($file->getRealPath()));
        $types = $this->getTypeRepository()->findAllWithLabelAsKey();

        foreach (
            ImportUtil::readFile(
                $file->getRealPath(),
                ImportUtil::formatHeader(...),
                static function (array &$row): void {
                    foreach ($row as &$col) {
                        if (StringUtil::isEmptyString($col) === false) {
                            $col = StringUtil::convertToUtf8($col);
                        }
                    }
                }
            ) as $row
        ) {
            ImportUtil::trimAndNullifyIfEmpty($row);
            $row = $resolver->resolve($row);
            $this->getEntityManager()->persist(
                (new Pokemon())
                    ->setId($row[ImportPokemonModel::COLUMN_POKEMON_NUMBER])
                    ->setName($row[ImportPokemonModel::COLUMN_POKEMON_NAME])
                    ->setHp($row[ImportPokemonModel::COLUMN_POKEMON_HP])
                    ->setAttack($row[ImportPokemonModel::COLUMN_POKEMON_ATK])
                    ->setDefense($row[ImportPokemonModel::COLUMN_POKEMON_DEF])
                    ->setSpeed($row[ImportPokemonModel::COLUMN_POKEMON_SPD])
                    ->setSpecial($row[ImportPokemonModel::COLUMN_POKEMON_SPE])
                    ->addType($types[$row[ImportPokemonModel::COLUMN_POKEMON_TYPE_1]])
                    ->addType(
                        $row[ImportPokemonModel::COLUMN_POKEMON_TYPE_2]
                        ? $types[$row[ImportPokemonModel::COLUMN_POKEMON_TYPE_2]] : null
                    )
            );
            $progressBar->advance();
        }
        $this->getEntityManager()->flush();
        $progressBar->finish();
        $io->newLine();
        $io->success('Pokémons created.');

        return Command::SUCCESS;
    }
}
