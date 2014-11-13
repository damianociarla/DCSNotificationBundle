<?php

namespace DCS\NotificationBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;

interface ComponentManagerInterface
{
    /**
     * Return the final class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Return the ObjectManager instance
     *
     * @return ObjectManager
     */
    public function getObjectManager();

    /**
     * Return an empty instance of ComponentInterface
     *
     * @return ComponentInterface
     */
    public function createComponent();

    /**
     * Persist the ComponentInterface
     *
     * @param ComponentInterface $component
     * @return void
     */
    public function save(ComponentInterface $component);

    /**
     * Find component by hash
     *
     * @param string $hash
     * @return ComponentInterface|null
     */
    public function findByHash($hash);

    /**
     * Find a Component. If not found it will be added automatically
     *
     * @param string $model
     * @param mixed $identifier
     * @return ComponentInterface
     */
    public function findOrCreateComponent($model, $identifier);
} 