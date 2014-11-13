<?php

namespace DCS\NotificationBundle\Model;

abstract class NotificationComponent implements NotificationComponentInterface
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
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}