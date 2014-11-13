<?php

namespace DCS\NotificationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActionsEnabledFormType extends AbstractType
{
    /**
     * @var array
     */
    protected $actions;

    function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->actions as $name => $configuration) {
            $transporters = array_keys($configuration['transporters']);
            $label = 'action.'.$name;

            $builder->add($name, 'choice', array(
                'label' => $label,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_combine(
                    $transporters,
                    array_map(function ($element) use ($label) {
                        return $label.'.'.$element;
                    }, $transporters)
                )
            ));
        }
    }

    public function getName()
    {
        return 'dcs_notification_actions_enabled';
    }
} 