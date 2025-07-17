<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $configurator) {
    $services = $configurator->services();

    // Default configuration for services
    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    // Register all services from the bundle
    $services->load('Excimetry\\ExcimetryBundle\\', '../../*')
        ->exclude('../../{DependencyInjection,Resources,Tests}');

    // Example service configuration
    $services->set('Excimetry\ExcimetryBundle\Service\DummyService')
        ->arg('$enabled', param('excimetry.enabled'))
        ->public();

    // Excimetry service
    $services->set('Excimetry\ExcimetryBundle\Service\ExcimetryService')
        ->args([
            '$enabled' => param('excimetry.enabled'),
            '$period' => param('excimetry.period'),
            '$mode' => param('excimetry.mode'),
        ])
        ->public();
};
