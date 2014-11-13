<?php

namespace DCS\NotificationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DCSNotificationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!in_array(strtolower($config['db_driver']), array('orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $container->setParameter('dcs_notification.model.component.class',              $config['class']['model']['component']);
        $container->setParameter('dcs_notification.model.component_setting.class',      $config['class']['model']['component_setting']);
        $container->setParameter('dcs_notification.model.notification.class',           $config['class']['model']['notification']);
        $container->setParameter('dcs_notification.model.notification_component.class', $config['class']['model']['notification_component']);
        $container->setParameter('dcs_notification.model.recipient.class',              $config['class']['model']['recipient']);

        $container->setParameter('dcs_notification.mode', $config['mode']);

        $container->setAlias('dcs_notification.manager.component', $config['service']['manager']['component']);
        $container->setAlias('dcs_notification.manager.component_setting', $config['service']['manager']['component_setting']);
        $container->setAlias('dcs_notification.manager.notification', $config['service']['manager']['notification']);

        $transporters = array();

        if (isset($config['transporters']) && count($config['transporters'])) {
            foreach ($config['transporters'] as $transporter => $transporterConfig) {
                $transporters[$transporter] = $transporterConfig['id'];
            }
            $container->setParameter('dcs_notification.transporters', $transporters);
        }

        if (isset($config['actions']) && count($config['actions'])) {
            foreach ($config['actions'] as $action => $actionConfig) {
                foreach ($actionConfig['transporters'] as $name => $transporterConfig) {
                    $transporterId = $transporterConfig['id'];
                    if (!array_key_exists($transporterId, $transporters)) {
                        throw new \Exception(sprintf('Transporter %s in %s action does not exists', $transporterId, $action));
                    }
                }
            }
            $container->setParameter('dcs_notification.actions', $config['actions']);
        }

        $loader->load('form.xml');
        $loader->load('listener.xml');
        $loader->load('notifier.xml');
        $loader->load('services.xml');
        $loader->load('transporter.xml');
    }
}
