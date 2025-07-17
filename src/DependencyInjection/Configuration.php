<?php

namespace Excimetry\ExcimetryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('excimetry');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                    ->info('Enable or disable the bundle')
                ->end()
                ->floatNode('period')
                    ->defaultValue(0.01)
                    ->info('The sampling period in seconds')
                ->end()
                ->enumNode('mode')
                    ->values(['wall', 'cpu'])
                    ->defaultValue('wall')
                    ->info('The profiling mode (wall or cpu)')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
