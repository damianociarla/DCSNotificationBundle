<?php

namespace DCS\NotificationBundle\Model;

interface RecipientInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get notification
     *
     * @return NotificationInterface
     */
    public function getNotification();

    /**
     * Set notification
     *
     * @param NotificationInterface $notification
     * @return RecipientInterface
     */
    public function setNotification($notification);

    /**
     * Get component
     *
     * @return ComponentInterface
     */
    public function getComponent();

    /**
     * Set component
     *
     * @param ComponentInterface $component
     * @return RecipientInterface
     */
    public function setComponent($component);
} 