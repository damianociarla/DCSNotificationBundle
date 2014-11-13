<?php

namespace DCS\NotificationBundle\Model;

interface NotificationInterface
{
    const STATUS_SENT       = 'sent';
    const STATUS_TO_SEND    = 'to_send';
    const STATUS_TO_RESEND  = 'to_resend';

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get subject
     *
     * @return ComponentInterface
     */
    public function getSubject();

    /**
     * Set subject
     *
     * @param ComponentInterface $subject
     * @return NotificationInterface
     */
    public function setSubject($subject);

    /**
     * Get action
     *
     * @return string
     */
    public function getAction();

    /**
     * Set action
     *
     * @param string $action
     * @return NotificationInterface
     */
    public function setAction($action);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return NotificationInterface
     * @throws \Exception
     */
    public function setStatus($status);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return NotificationInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get sentAt
     *
     * @return \DateTime
     */
    public function getSentAt();

    /**
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return NotificationInterface
     */
    public function setSentAt($sentAt);

    /**
     * Return all status
     *
     * @return array
     */
    public static function getStatusValues();
} 