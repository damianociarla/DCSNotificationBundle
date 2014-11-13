<?php

namespace DCS\NotificationBundle\Model;

interface ComponentSettingInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get component
     *
     * @return ComponentInterface
     */
    public function getComponent();

    /**
     * Set component
     *
     * @param ComponentInterface $component
     * @return ComponentSettingInterface
     */
    public function setComponent($component);

    /**
     * Get actionsEnabled
     *
     * @return array
     */
    public function getActionsEnabled();

    /**
     * Set actionsEnabled
     *
     * @param array $actionsEnabled
     * @return ComponentSettingInterface
     */
    public function setActionsEnabled($actionsEnabled);

    /**
     * Set actionsEnabled
     *
     * @param string $actionEnabled
     * @return ComponentSettingInterface
     */
    public function addActionEnabled($actionEnabled);
} 