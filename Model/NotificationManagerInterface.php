<?php

namespace DCS\NotificationBundle\Model;

interface NotificationManagerInterface
{
    /**
     * Return the final class name of the Notification
     *
     * @return string
     */
    public function getNotificationClass();

    /**
     * Return the final class name NotificationComponent
     *
     * @return string
     */
    public function getNotificationComponentClass();

    /**
     * Return the final class name of the Recipient
     *
     * @return string
     */
    public function getRecipientClass();

    /**
     * Return an empty instance of NotificationInterface
     *
     * @param string $action
     * @return NotificationInterface
     * @throws \Exception
     */
    public function createNotification($action);

    /**
     * Return an empty instance of NotificationComponentInterface
     *
     * @return NotificationComponentInterface
     * @throws \Exception
     */
    public function createNotificationComponent();

    /**
     * Return an empty instance of RecipientInterface
     *
     * @param NotificationInterface $notification
     * @param ComponentInterface $component
     * @return RecipientInterface
     */
    public function createRecipient(NotificationInterface $notification, ComponentInterface $component);

    /**
     * Persist the NotificationInterface
     *
     * @param NotificationInterface $notification
     * @return void
     */
    public function saveNotification(NotificationInterface $notification);

    /**
     * Persist the NotificationComponentInterface
     *
     * @param NotificationComponentInterface $notificationComponent
     * @return void
     */
    public function saveNotificationComponent(NotificationComponentInterface $notificationComponent);

    /**
     * Persist the RecipientInterface
     *
     * @param RecipientInterface $recipient
     * @param boolean $unique
     * @return void
     */
    public function saveRecipient(RecipientInterface $recipient, $unique = true);

    /**
     * Find all notification to send
     *
     * @param int $limit
     * @return array
     */
    public function findAllNotificationToSend($limit);

    /**
     * Find all notification components by NotificationInterface
     *
     * @param NotificationInterface $notification
     * @return array
     */
    public function findAllNotificationComponent(NotificationInterface $notification);

    /**
     * Find one recipient by NotificationInterface and ComponentInterface
     *
     * @param NotificationInterface $notification
     * @param ComponentInterface $component
     * @return RecipientInterface|null
     */
    public function findOneRecipientByNotificationAndComponent(NotificationInterface $notification, ComponentInterface $component);

    /**
     * Find all recipients of the NotificationInterface
     *
     * @param NotificationInterface $notification
     * @return array
     */
    public function findAllRecipientsByNotification(NotificationInterface $notification);
} 