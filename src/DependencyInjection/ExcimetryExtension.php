<?php

namespace Excimetry\ExcimetryBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages the bundle configuration.
 */
class ExcimetryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Set the parameters
        $container->setParameter('excimetry.enabled', $config['enabled']);
        $container->setParameter('excimetry.period', $config['period']);
        $container->setParameter('excimetry.mode', $config['mode']);

        // Load services
        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');

        // Load YAML services
        $yamlLoader = new YamlFileLoader($container, $fileLocator);
        $yamlLoader->load('services.yaml');

        // Load PHP services if they exist
        $phpLoader = new PhpFileLoader($container, $fileLocator);
        if (file_exists(__DIR__ . '/../Resources/config/services.php')) {
            $phpLoader->load('services.php');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'excimetry';
    }
}