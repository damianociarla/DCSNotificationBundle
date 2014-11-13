<?php

namespace DCS\NotificationBundle\Resolver;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Persistence\ObjectManager;

class Resolver implements ResolverInterface
{
    protected $objectManager;

    function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function resolve(ResolverComponentModelIdentifierInterface $resolverComponent)
    {
        $model = $resolverComponent->getModel();
        $identifier = $resolverComponent->getIdentifier();

        $data = null;

        if (class_exists($model)) {
            try {
                $repository = $this->objectManager->getRepository($model);
                if (is_scalar($identifier)) {
                    $data = $repository->find($identifier);
                } elseif (is_array($identifier)) {
                    $data = $repository->findOneBy($identifier);
                }
            } catch (MappingException $e) {
                $data = new $model();
                if (is_scalar($identifier)) {
                    if (method_exists($data, 'setId')) {
                        $data->setId($identifier);
                    }
                } elseif (is_array($identifier)) {
                    foreach ($identifier as $property => $value) {
                        if (is_string($property)) {
                            $methodName = 'set'.ucfirst($property);
                            if (method_exists($data, $methodName)) {
                                $data->{$methodName}($value);
                            }
                        }
                    }
                }
            }
        } else {
            $data = array($model => $identifier);
        }

        return $data;
    }
}