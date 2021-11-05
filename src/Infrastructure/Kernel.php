<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();
        
        $container->import($configDir . '/{packages}/*.yaml');
        $container->import($configDir . '/{packages}/'.$this->environment.'/*.yaml');
        $container->import($configDir . '/{packages}/{doctrine}/**/*.yaml');

        if (is_file($configDir . '/services.yaml')) {
            $container->import($configDir . '/services.yaml');
            $container->import($configDir . '/{services}_'.$this->environment.'.yaml');
        } else {
            $container->import($configDir . '/{services}.php');
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();
        
        $routes->import($configDir . '/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($configDir . '/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
            $routes->import($configDir . '/routes.yaml');
        } else {
            $routes->import($configDir . '/{routes}.php');
        }
    }
    
    protected function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }
}
