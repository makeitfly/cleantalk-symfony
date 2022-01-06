<?php

namespace MakeItFly\CleanTalkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('makeitfly_cleantalk');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enabled')
                    ->defaultValue(true)
                    ->end()
                ->scalarNode('server_url')
                    ->defaultValue('https://moderate.cleantalk.org/api2.0/')
                    ->end()
                ->scalarNode('auth_key')
                    ->isRequired()
                    ->end()
                ->scalarNode('agent')
                    ->defaultValue('makeitfly-symfony')
            ->end();

        return $treeBuilder;
    }

}
