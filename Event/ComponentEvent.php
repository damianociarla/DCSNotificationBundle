<?php

namespace DCS\NotificationBundle\Event;

use DCS\NotificationBundle\Model\ComponentInterface;
use Symfony\Component\EventDispatcher\Event;

class ComponentEvent extends Event
{
    /**
     * @var ComponentInterface
     */
    private $component;

    function __construct(ComponentInterface $component)
    {
        $this->component = $component;
    }

    /**
     * Get component
     *
     * @return ComponentInterface
     */
    public function getComponent()
    {
        return $this->component;
    }
} 