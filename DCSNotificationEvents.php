<?php

namespace DCS\NotificationBundle;

class DCSNotificationEvents
{
    /**
     * Event triggered when the function "ComponentManager::findOrCreateComponent" does not finds no component and creates a new one
     * The Listener will receive an instance of ComponentEvent
     *
     * @see DCS\NotificationBundle\Event\ComponentEvent
     */
    const COMPONENT_CREATED = 'dcs_notification.event.component_created';

    /**
     * Event triggered before of the persist of the notification
     * The listener will receive an instance of NotificationEvent
     *
     * @see DCS\NotificationBundle\Event\NotificationEvent
     */
    const NOTIFICATION_PRE_PERSIST = 'dcs_notification.event.notification.pre_persist';

    /**
     * Event triggered after of the persist of the notification
     * The listener will receive an instance of NotificationEvent
     *
     * @see DCS\NotificationBundle\Event\NotificationEvent
     */
    const NOTIFICATION_POST_PERSIST = 'dcs_notification.event.notification.post_persist';
}