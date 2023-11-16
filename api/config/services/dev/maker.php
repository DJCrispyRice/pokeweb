<?php

declare(strict_types=1);

use App\Command\Maker\MakeAutowireCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $makerServices = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $makerServices
        ->set(MakeAutowireCommand::class)
        ->bind('$projectDir', '%kernel.project_dir%')
    ;
};
