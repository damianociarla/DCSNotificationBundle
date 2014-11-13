<?php

namespace DCS\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class NotifierCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dcs_notification.notifier.chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'dcs_notification.notifier.chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'dcs_notification.notifier'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addNotifier',
                array(new Reference($id))
            );
        }
    }
}