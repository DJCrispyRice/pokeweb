<?php

declare(strict_types=1);

namespace App\Command;

use App\Autowire\Dependencies\Doctrine\ORM\AutowireEntityManagerInterfaceTrait;
use App\Autowire\Repository\AutowireAttackRepositoryTrait;
use App\Autowire\Repository\AutowirePokemonRepositoryTrait;
use App\Command\Model\ImportAffectationModel;
use App\Command\Traits\ImportTrait;
use App\Entity\Attack;
use App\Entity\Pokemon;
use App\Util\Import\ImportUtil;
use App\Util\String\StringUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-affectations')]
final class CreateAffectationsCommand extends Command
{
    use AutowireAttackRepositoryTrait;
    use AutowireEntityManagerInterfaceTrait;
    use AutowirePokemonRepositoryTrait;
    use ImportTrait;

    private const FILE = 'affectation.csv';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Affecting attacks to pokemon...');
        $resolver = ImportAffectationModel::buildOptionsResolver();
        $file = $this->openCsvFile(self::FILE);
        if ($file === null) {
            $io->error('The affectation file was not found. Exiting');

            return Command::FAILURE;
        }
        $progressBar = new ProgressBar($output, ImportUtil::countRow($file->getRealPath()));
        $pokemons = $this->getPokemonRepository()->findAllWithNameAsKey();
        $attacks = $this->getAttackRepository()->findAllWithLabelAsKey();

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
            $this->affectAttack(
                $pokemons[$row[ImportAffectationModel::COLUMN_AFFECTATION_POKEMON]],
                $attacks[$row[ImportAffectationModel::COLUMN_AFFECTATION_ATTACK_1]]
            );
            $this->affectAttack(
                $pokemons[$row[ImportAffectationModel::COLUMN_AFFECTATION_POKEMON]],
                $attacks[$row[ImportAffectationModel::COLUMN_AFFECTATION_ATTACK_2]] ?? null
            );
            $this->affectAttack(
                $pokemons[$row[ImportAffectationModel::COLUMN_AFFECTATION_POKEMON]],
                $attacks[$row[ImportAffectationModel::COLUMN_AFFECTATION_ATTACK_3]] ?? null
            );
            $this->affectAttack(
                $pokemons[$row[ImportAffectationModel::COLUMN_AFFECTATION_POKEMON]],
                $attacks[$row[ImportAffectationModel::COLUMN_AFFECTATION_ATTACK_4]] ?? null
            );
            $progressBar->advance();
        }
        $this->getEntityManager()->flush();
        $progressBar->finish();
        $io->newLine();
        $io->success('Attacks affected to Pokémons.');

        return Command::SUCCESS;
    }

    private function affectAttack(Pokemon $pokemon, ?Attack $attack = null): void
    {
        if ($attack !== null) {
            $pokemon->addAttack($attack);
        }
    }
}
