<?php

namespace DCS\NotificationBundle\Listener;

use DCS\NotificationBundle\DCSNotificationEvents;
use DCS\NotificationBundle\Event\ComponentEvent;
use DCS\NotificationBundle\Model\ComponentSettingManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DCSNotificationEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ComponentSettingManagerInterface
     */
    protected $componentSettingManager;

    /**
     * @var array
     */
    protected $actions;

    function __construct(ComponentSettingManagerInterface $componentSettingManager, array $actions)
    {
        $this->componentSettingManager = $componentSettingManager;
        $this->actions = $actions;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DCSNotificationEvents::COMPONENT_CREATED => array('onComponentCreated'),
        );
    }

    public function onComponentCreated(ComponentEvent $event)
    {
        $actionsEnabled = array();

        foreach ($this->actions as $name => $action) {
            $actionsEnabled[$name] = array_keys($action['transporters']);
        }

        $componentSetting = $this->componentSettingManager->findOrCreateComponentSetting($event->getComponent());
        $componentSetting->setActionsEnabled($actionsEnabled);

        $this->componentSettingManager->save($componentSetting);
    }
} 