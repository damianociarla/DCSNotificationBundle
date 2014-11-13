<?php

namespace DCS\NotificationBundle\Service;

use DCS\NotificationBundle\Model\ComponentManagerInterface;
use DCS\NotificationBundle\Model\ComponentSettingManagerInterface;
use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\NotificationManagerInterface;
use DCS\NotificationBundle\Model\RecipientInterface;
use DCS\NotificationBundle\Notifier\Collection\RecipientCollection;
use DCS\NotificationBundle\Notifier\NotifierChain;
use DCS\NotificationBundle\Notifier\NotifierInterface;
use DCS\NotificationBundle\Transporter\TransporterChain;

class NotifierService
{
    /**
     * @var array
     */
    protected $actions;

    /**
     * @var ComponentManagerInterface
     */
    protected $componentManager;

    /**
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * @var ComponentSettingManagerInterface
     */
    protected $componentSettingManager;

    /**
     * @var NotifierChain
     */
    protected $notifier;

    /**
     * @var TransporterChain
     */
    protected $transporter;

    function __construct(
        $actions,
        ComponentManagerInterface $componentManager,
        NotificationManagerInterface $notificationManager,
        ComponentSettingManagerInterface $componentSettingManager,
        NotifierChain $notifier,
        TransporterChain $transporter
    ) {
        $this->actions = $actions;
        $this->componentManager = $componentManager;
        $this->notificationManager = $notificationManager;
        $this->componentSettingManager = $componentSettingManager;
        $this->notifier = $notifier;
        $this->transporter = $transporter;
    }

    /**
     * Process the NotificationInterface
     *
     * @param NotificationInterface $notification
     */
    public function process(NotificationInterface $notification)
    {
        if ($notification->getStatus() != NotificationInterface::STATUS_SENT) {
            $this->callNotifier($notification);
            $this->callTransporter($notification);

            $notification->setStatus(NotificationInterface::STATUS_SENT);
            $notification->setSentAt(new \DateTime());

            $this->notificationManager->saveNotification($notification);
        }
    }

    /**
     * Call all notifiers for the NotificationInterface
     *
     * @param NotificationInterface $notification
     */
    public function callNotifier(NotificationInterface $notification)
    {
        $notifiers = $this->notifier->getNotifiers();
        $recipientCollection = new RecipientCollection($this->componentManager);

        /** @var NotifierInterface $notifier */
        foreach ($notifiers as $notifier) {
            if ($notifier->supports($notification)) {
                $notifier->notify($notification, $recipientCollection);
            }
        }

        $recipients = $recipientCollection->getIterator();

        foreach ($recipients as $component) {
            $recipient = $this->notificationManager->createRecipient($notification, $component);
            $this->notificationManager->saveRecipient($recipient);
        }
    }

    /**
     * Call all transporter for the action of the NotificationInterface
     *
     * @param NotificationInterface $notification
     */
    public function callTransporter(NotificationInterface $notification)
    {
        $configuration = $this->getConfiguration($notification->getAction());

        if (null === $configuration) {
            return null;
        }

        if (isset($configuration['transporters'])) {
            $notificationComponents = $this->notificationManager->findAllNotificationComponent($notification);
            $notificationRecipients = $this->notificationManager->findAllRecipientsByNotification($notification);

            /** @var RecipientInterface $notificationRecipient */
            foreach ($notificationRecipients as $notificationRecipient) {
                $recipientComponent = $notificationRecipient->getComponent();
                $settings = $this->componentSettingManager->findOrCreateComponentSetting($recipientComponent);
                $actionsEnabled = $settings->getActionsEnabled();

                if (array_key_exists($notification->getAction(), $actionsEnabled)) {
                    foreach ($configuration['transporters'] as $name => $params) {
                        if (in_array($name, $actionsEnabled[$notification->getAction()])) {
                            $transporterId = $params['id'];
                            if (null !== $transporter = $this->transporter->getTransporter($transporterId)) {
                                $transporter->setConfiguration(isset($params['config']) ? $params['config'] : array());
                                $transporter->send($notification, $notificationComponents, $notificationRecipient);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Return the configuration for the action name
     *
     * @param string $name
     * @return array|null
     */
    private function getConfiguration($name)
    {
        if (isset($this->actions[$name])) {
            return $this->actions[$name];
        }

        return null;
    }
}