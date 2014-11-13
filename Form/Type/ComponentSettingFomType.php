<?php

namespace DCS\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComponentSettingFomType extends AbstractType
{
    /**
     * @var string
     */
    protected $componentSettingClassName;

    function __construct($componentSettingClassName)
    {
        $this->componentSettingClassName = $componentSettingClassName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('actionsEnabled', 'dcs_notification_actions_enabled');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->componentSettingClassName,
        ));
    }

    public function getName()
    {
        return 'dcs_notification_component_setting';
    }
} 