<?php

declare(strict_types=1);

namespace App\Command;

use App\Autowire\Dependencies\Doctrine\ORM\AutowireEntityManagerInterfaceTrait;
use App\Autowire\Dependencies\Symfony\Component\DependencyInjection\ParameterBag\AutowireParameterBagInterfaceTrait;
use App\Autowire\Repository\AutowireTypeRepositoryTrait;
use App\Command\Model\ImportAttackModel;
use App\Command\Traits\ImportTrait;
use App\Entity\Attack;
use App\Util\Import\ImportUtil;
use App\Util\String\StringUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-attacks')]
final class CreateAttacksCommand extends Command
{
    use AutowireEntityManagerInterfaceTrait;
    use AutowireParameterBagInterfaceTrait;
    use AutowireTypeRepositoryTrait;
    use ImportTrait;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Creating attacks...');
        $resolver = ImportAttackModel::buildOptionsResolver();
        $file = $this->openCsvFile('atk.csv');
        if ($file === null) {
            $io->error('The attack base file was not found. Exiting');

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
                (new Attack())
                    ->setLabel($row[ImportAttackModel::COLUMN_ATTACK_NAME])
                    ->setType($types[$row[ImportAttackModel::COLUMN_ATTACK_TYPE]])
                    ->setIsPhysical($row[ImportAttackModel::COLUMN_ATTACK_PHYSICAL] === 'Physical')
                    ->setPower($row[ImportAttackModel::COLUMN_ATTACK_POWER])
                    ->setAccuracy($row[ImportAttackModel::COLUMN_ATTACK_ACCURACY])
                    ->setPp($row[ImportAttackModel::COLUMN_ATTACK_PP])
                    ->setDescription($row[ImportAttackModel::COLUMN_ATTACK_DESCRIPTION])
            );
            $progressBar->advance();
        }
        $this->getEntityManager()->flush();
        $progressBar->finish();
        $io->newLine();
        $io->success('Attacks created.');

        return Command::SUCCESS;
    }
}
