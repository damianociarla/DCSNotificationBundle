<?php

namespace DCS\NotificationBundle\Notifier\Collection;

use DCS\NotificationBundle\Model\ComponentInterface;

interface RecipientCollectionInterface
{
    /**
     * Adds a new component to the recipients of the notification
     *
     * @param $model
     * @param $identifier
     * @return void
     */
    public function add($model, $identifier);

    /**
     * Adds a component to the recipients of the notification
     *
     * @param ComponentInterface $component
     * @return void
     */
    public function addComponent(ComponentInterface $component);

    /**
     * Checks whether an element is contained in the collection.
     *
     * @param ComponentInterface $component
     * @return boolean
     */
    public function contains(ComponentInterface $component);
} 