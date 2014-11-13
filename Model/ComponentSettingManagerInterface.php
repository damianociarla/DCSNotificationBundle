<?php

namespace DCS\NotificationBundle\Model;

interface ComponentSettingManagerInterface
{
    /**
     * Return the final class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Return an empty instance of ComponentSettingInterface
     *
     * @param ComponentInterface $component
     * @return ComponentSettingInterface
     */
    public function createComponentSetting(ComponentInterface $component);

    /**
     * Persist the ComponentSettingInterface
     *
     * @param ComponentSettingInterface $componentSetting
     * @return void
     */
    public function save(ComponentSettingInterface $componentSetting);

    /**
     * Find setting for ComponentInterface
     *
     * @param ComponentInterface $component
     * @return ComponentSettingInterface|null
     */
    public function findByComponent(ComponentInterface $component);

    /**
     * Find a Component. If not found it will be added automatically
     *
     * @param ComponentInterface $component
     * @return ComponentSettingInterface
     */
    public function findOrCreateComponentSetting(ComponentInterface $component);
} 