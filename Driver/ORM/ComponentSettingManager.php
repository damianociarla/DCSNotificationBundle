<?php

namespace DCS\NotificationBundle\Driver\ORM;

use DCS\NotificationBundle\Model\ComponentSettingInterface;
use DCS\NotificationBundle\Model\ComponentSettingManager as BaseComponentSettingManager;
use Doctrine\ORM\EntityManager;

class ComponentSettingManager extends BaseComponentSettingManager
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    function __construct($className, EntityManager $entityManager)
    {
        $this->className = $className;
        $this->entityManager = $entityManager;
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
    protected function doSave(ComponentSettingInterface $componentSetting)
    {
        $this->entityManager->persist($componentSetting);
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