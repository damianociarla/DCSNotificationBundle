<?php

namespace DCS\NotificationBundle\Model;

abstract class ComponentSetting implements ComponentSettingInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ComponentInterface
     */
    protected $component;

    /**
     * @var array
     */
    protected $actionsEnabled;

    function __construct()
    {
        $this->actionsEnabled = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * {@inheritdoc}
     */
    public function setComponent($component)
    {
        $this->component = $component;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActionsEnabled()
    {
        return $this->actionsEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setActionsEnabled($actionsEnabled)
    {
        $this->actionsEnabled = $actionsEnabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addActionEnabled($actionEnabled)
    {
        if (!in_array($actionEnabled, $this->actionsEnabled)) {
            $this->actionsEnabled[] = $actionEnabled;
        }

        return $this;
    }
}