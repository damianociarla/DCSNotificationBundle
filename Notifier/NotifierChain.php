<?php

namespace DCS\NotificationBundle\Notifier;

class NotifierChain
{
    private $notifiers;

    public function __construct()
    {
        $this->notifiers = array();
    }

    public function addNotifier(NotifierInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * Get notifiers
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }
}