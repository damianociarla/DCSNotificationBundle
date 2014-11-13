<?php

namespace DCS\NotificationBundle\Driver\ORM;

use DCS\NotificationBundle\Model\ComponentInterface;
use DCS\NotificationBundle\Model\ComponentManager as BaseComponentManager;
use DCS\NotificationBundle\Resolver\Resolver;
use DCS\NotificationBundle\Resolver\ResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ComponentManager extends BaseComponentManager
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    function __construct($className, EntityManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        parent::__construct(new Resolver($entityManager), $dispatcher);

        $this->className = $className;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectManager()
    {
        return $this->entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function findOneBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->className)->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave(ComponentInterface $component)
    {
        $this->entityManager->persist($component);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->className;
    }
} 