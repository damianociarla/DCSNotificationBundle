<?php

namespace DCS\NotificationBundle\Notifier;

use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Notifier\Collection\RecipientCollectionInterface;

interface NotifierInterface
{
    /**
     * Checks if a notification is supported by the notifier
     *
     * @param NotificationInterface $notification
     * @return boolean
     */
    public function supports(NotificationInterface $notification);

    /**
     * Sets the notification recipients
     *
     * @param NotificationInterface $notification
     * @param RecipientCollectionInterface $recipients
     * @return void
     */
    public function notify(NotificationInterface $notification, RecipientCollectionInterface $recipients);
} 