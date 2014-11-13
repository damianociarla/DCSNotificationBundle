<?php

namespace DCS\NotificationBundle;

use DCS\NotificationBundle\DependencyInjection\Compiler\NotifierCompilerPass;
use DCS\NotificationBundle\DependencyInjection\Compiler\TransporterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DCSNotificationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NotifierCompilerPass());
        $container->addCompilerPass(new TransporterCompilerPass());
    }
}
