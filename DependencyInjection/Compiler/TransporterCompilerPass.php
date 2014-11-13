<?php

namespace DCS\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class TransporterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dcs_notification.transporter.chain') ||
            !$container->hasParameter('dcs_notification.transporters')
        ) {
            return;
        }

        $definition = $container->getDefinition(
            'dcs_notification.transporter.chain'
        );

        $transporters = $container->getParameter('dcs_notification.transporters');

        foreach ($transporters as $name => $serviceId) {
            $definition->addMethodCall(
                'addTransporter',
                array(new Reference($serviceId), $name)
            );
        }
    }
}