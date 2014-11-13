<?php

namespace DCS\NotificationBundle\Model;

interface NotificationComponentInterface
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
     * @return NotificationComponentInterface
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
     * @return NotificationComponentInterface
     */
    public function setComponent($component);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     * @return NotificationComponentInterface
     */
    public function setType($type);
} 