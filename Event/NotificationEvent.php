<?php

namespace DCS\NotificationBundle\Event;

use DCS\NotificationBundle\Model\NotificationInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    /**
     * @var NotificationInterface
     */
    protected $notification;

    function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get notification
     *
     * @return NotificationInterface
     */
    public function getNotification()
    {
        return $this->notification;
    }
} 