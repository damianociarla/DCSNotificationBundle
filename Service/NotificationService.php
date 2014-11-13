<?php

namespace DCS\NotificationBundle\Service;

use DCS\NotificationBundle\Model\ComponentInterface;
use DCS\NotificationBundle\Model\ComponentManagerInterface;
use DCS\NotificationBundle\Model\NotificationManagerInterface;

class NotificationService
{
    /**
     * @var string
     */
    protected $mode;

    /**
     * @var ComponentManagerInterface
     */
    protected $componentManager;

    /**
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * @var NotifierService
     */
    protected $notifierService;

    function __construct($mode, ComponentManagerInterface $componentManager, NotificationManagerInterface $notificationManager, NotifierService $notifierService)
    {
        $this->mode = $mode;
        $this->componentManager = $componentManager;
        $this->notificationManager = $notificationManager;
        $this->notifierService = $notifierService;
    }

    /**
     * @param ComponentInterface $subject
     * @param $action
     * @param array $components
     * @return \DCS\NotificationBundle\Model\NotificationInterface
     * @throws \Exception
     */
    public function notify(ComponentInterface $subject, $action, array $components = null, $forceNotify = false)
    {
        $notification = $this->notificationManager->createNotification($action);
        $notification->setSubject($subject);

        $this->notificationManager->saveNotification($notification);

        if (null !== $components && count($components)) {
            foreach ($components as $type => $component) {
                if (!($component instanceof ComponentInterface)) {
                    throw new \Exception('The components %s is not a ComponentInterface. Use the method "findOrCreateComponent"', $type);
                }

                $notificationComponent = $this->notificationManager->createNotificationComponent();
                $notificationComponent->setNotification($notification);
                $notificationComponent->setComponent($component);
                $notificationComponent->setType($type);

                $this->notificationManager->saveNotificationComponent($notificationComponent);
            }
        }

        if ($forceNotify || $this->mode == 'runtime') {
            $this->notifierService->process($notification);
        }

        return $notification;
    }
} 