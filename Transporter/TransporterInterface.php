<?php

namespace DCS\NotificationBundle\Transporter;

use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\RecipientInterface;

interface TransporterInterface
{
    /**
     * Set configurations
     *
     * @param array $config
     * @return void
     */
    public function setConfiguration($config);

    /**
     * Send notification
     *
     * @param NotificationInterface $notification
     * @param array $components
     * @param RecipientInterface $recipient
     * @return void
     */
    public function send(NotificationInterface $notification, array $components, RecipientInterface $recipient);
} 