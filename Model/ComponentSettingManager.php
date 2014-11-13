<?php

namespace DCS\NotificationBundle\Model;

abstract class ComponentSettingManager implements ComponentSettingManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createComponentSetting(ComponentInterface $component)
    {
        $class = $this->getClass();

        $componentSetting = new $class();
        $componentSetting->setComponent($component);

        return $componentSetting;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ComponentSettingInterface $componentSetting)
    {
        $this->doSave($componentSetting);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrCreateComponentSetting(ComponentInterface $component)
    {
        if (null === $componentSetting = $this->findByComponent($component)) {
            $componentSetting = $this->createComponentSetting($component);
            $this->save($componentSetting);
        }

        return $componentSetting;
    }

    /**
     * {@inheritdoc}
     */
    public function findByComponent(ComponentInterface $component)
    {
        return $this->findOneBy(array(
            'component' => $component
        ));
    }

    /**
     * Find ComponentSettingInterface by criteria
     *
     * @param array $criteria
     * @return ComponentSettingInterface|null
     */
    abstract protected function findOneBy(array $criteria);

    /**
     * Performs the persistence of the ComponentSettingInterface
     *
     * @param ComponentSettingInterface $componentSetting
     * @return void
     */
    abstract protected function doSave(ComponentSettingInterface $componentSetting);
} 