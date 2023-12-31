<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.{php,yaml}');
        $container->import('../config/{packages}/' . $this->environment . '/*.{php,yaml}');

        $container->import('../config/{services}/*.{php,yaml}');
        $container->import('../config/{services}/' . $this->environment . '/*.{php,yaml}');
    }
}
