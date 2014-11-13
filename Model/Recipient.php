<?php

namespace DCS\NotificationBundle\Model;

abstract class Recipient implements RecipientInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var NotificationInterface
     */
    protected $notification;

    /**
     * @var ComponentInterface
     */
    protected $component;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * {@inheritdoc}
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * {@inheritdoc}
     */
    public function setComponent($component)
    {
        $this->component = $component;

        return $this;
    }
}