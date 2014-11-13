<?php

namespace DCS\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('dcs_notification')
            ->children()
                ->scalarNode('db_driver')
                    ->cannotBeOverwritten()
                    ->isRequired()
                ->end()
                ->arrayNode('transporters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('actions')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('transporters')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('id')->isRequired()->end()
                                        ->variableNode('config')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('class')->isRequired()
                    ->children()
                        ->arrayNode('model')->isRequired()
                            ->children()
                                ->scalarNode('component')->isRequired()->end()
                                ->scalarNode('component_setting')->isRequired()->end()
                                ->scalarNode('notification')->isRequired()->end()
                                ->scalarNode('notification_component')->isRequired()->end()
                                ->scalarNode('recipient')->isRequired()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('service')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('manager')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('component')
                                    ->cannotBeEmpty()
                                    ->defaultValue('dcs_notification.component_manager.default')
                                ->end()
                                ->scalarNode('component_setting')
                                    ->cannotBeEmpty()
                                    ->defaultValue('dcs_notification.component_setting_manager.default')
                                ->end()
                                ->scalarNode('notification')
                                    ->cannotBeEmpty()
                                    ->defaultValue('dcs_notification.notification_manager.default')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('mode')
                    ->defaultValue('schedule')
                    ->validate()
                    ->ifNotInArray(array('schedule', 'runtime'))
                        ->thenInvalid('Value "%s" is not a valid mode')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
