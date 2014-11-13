<?php

namespace DCS\NotificationBundle\Model;

use DCS\NotificationBundle\DCSNotificationEvents;
use DCS\NotificationBundle\Event\NotificationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class NotificationManager implements NotificationManagerInterface
{
    /**
     * Actions configured
     *
     * @var array
     */
    protected $actions;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    function __construct($actions, EventDispatcherInterface $dispatcher)
    {
        $this->actions = $actions;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createNotification($action)
    {
        if (!array_key_exists($action, $this->actions)) {
            throw new \Exception(sprintf('Action %s does not exists', $action));
        }

        $className = $this->getNotificationClass();

        $class = new $className();
        $class->setAction($action);

        return $class;
    }

    /**
     * {@inheritdoc}
     */
    public function createNotificationComponent()
    {
        $className = $this->getNotificationComponentClass();
        return new $className();
    }

    /**
     * {@inheritdoc}
     */
    public function createRecipient(NotificationInterface $notification, ComponentInterface $component)
    {
        $className = $this->getRecipientClass();

        $class = new $className();
        $class->setNotification($notification);
        $class->setComponent($component);

        return $class;
    }

    /**
     * {@inheritdoc}
     */
    public function saveNotification(NotificationInterface $notification)
    {
        $event = new NotificationEvent($notification);

        $this->dispatcher->dispatch(DCSNotificationEvents::NOTIFICATION_PRE_PERSIST, $event);

        $this->doSaveNotification($notification);

        $this->dispatcher->dispatch(DCSNotificationEvents::NOTIFICATION_POST_PERSIST, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function saveNotificationComponent(NotificationComponentInterface $notificationComponent)
    {
        $this->doSaveNotificationComponent($notificationComponent);
    }

    /**
     * {@inheritdoc}
     */
    public function saveRecipient(RecipientInterface $recipient, $unique = true)
    {
        if (!$unique || ($unique && null === $this->findOneRecipientByNotificationAndComponent($recipient->getNotification(), $recipient->getComponent()))) {
            $this->doSaveRecipient($recipient);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAllNotificationComponent(NotificationInterface $notification)
    {
        return $this->findAllNotificationComponentBy(array(
            'notification' => $notification
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function findOneRecipientByNotificationAndComponent(NotificationInterface $notification, ComponentInterface $component)
    {
        $recipients = $this->findAllRecipientBy(array(
            'notification' => $notification,
            'component' => $component,
        ));

        if (count($recipients) > 0) {
            return $recipients[0];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllRecipientsByNotification(NotificationInterface $notification)
    {
        return $this->findAllRecipientBy(array(
            'notification' => $notification,
        ));
    }

    /**
     * Find all notification components by the criteria
     *
     * @param array $criteria
     * @return array
     */
    abstract protected function findAllNotificationComponentBy(array $criteria);

    /**
     * Find all recipients by the criteria
     *
     * @param array $criteria
     * @return array
     */
    abstract protected function findAllRecipientBy(array $criteria);

    /**
     * Performs the persistence of the NotificationInterface
     *
     * @param NotificationInterface $notification
     * @return void
     */
    abstract protected function doSaveNotification(NotificationInterface $notification);

    /**
     * Performs the persistence of the NotificationComponentInterface
     *
     * @param NotificationComponentInterface $notificationComponent
     * @return void
     */
    abstract protected function doSaveNotificationComponent(NotificationComponentInterface $notificationComponent);

    /**
     * Performs the persistence of the RecipientInterface
     *
     * @param RecipientInterface $recipient
     * @return void
     */
    abstract protected function doSaveRecipient(RecipientInterface $recipient);
} 