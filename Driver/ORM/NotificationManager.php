<?php

namespace DCS\NotificationBundle\Driver\ORM;

use DCS\NotificationBundle\Model\NotificationComponentInterface;
use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\NotificationManager as BaseNotificationManager;
use DCS\NotificationBundle\Model\RecipientInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotificationManager extends BaseNotificationManager
{
    /**
     * @var string
     */
    protected $notificationClassName;

    /**
     * @var string
     */
    protected $notificationComponentClassName;

    /**
     * @var string
     */
    protected $recipientClassName;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    function __construct(
        array $actions,
        $notificationClassName,
        $notificationComponentClassName,
        $recipientClassName,
        EntityManager $entityManager,
        EventDispatcherInterface $dispatcher
    ) {
        parent::__construct($actions, $dispatcher);

        $this->notificationClassName = $notificationClassName;
        $this->notificationComponentClassName = $notificationComponentClassName;
        $this->recipientClassName = $recipientClassName;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotificationClass()
    {
        return $this->notificationClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotificationComponentClass()
    {
        return $this->notificationComponentClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipientClass()
    {
        return $this->recipientClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllNotificationComponentBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->notificationComponentClassName)->findBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    protected function findAllRecipientBy(array $criteria)
    {
        return $this->entityManager->getRepository($this->recipientClassName)->findBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findAllNotificationToSend($limit)
    {
        return $this->entityManager->getRepository($this->notificationClassName)->findBy(array(
            'status' => 'to_send'
        ), null, $limit);
    }

    /**
     * {@inheritdoc}
     */
    protected function doSaveNotification(NotificationInterface $notification)
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function doSaveNotificationComponent(NotificationComponentInterface $notificationComponent)
    {
        $this->entityManager->persist($notificationComponent);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function doSaveRecipient(RecipientInterface $recipient)
    {
        $this->entityManager->persist($recipient);
        $this->entityManager->flush();
    }
} 