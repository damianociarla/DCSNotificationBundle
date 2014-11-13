<?php

namespace DCS\NotificationBundle\Listener;

use DCS\NotificationBundle\Model\ComponentInterface;
use DCS\NotificationBundle\Resolver\Resolver;
use DCS\NotificationBundle\Resolver\ResolverComponentModelIdentifier;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class DoctrineEventListener
{
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof ComponentInterface) {
            $resolver = new Resolver($args->getObjectManager());
            $resolverComponent = new ResolverComponentModelIdentifier(
                $object->getModel(),
                $object->getIdentifier()
            );

            $object->setData($resolver->resolve($resolverComponent));
        }
    }
} 