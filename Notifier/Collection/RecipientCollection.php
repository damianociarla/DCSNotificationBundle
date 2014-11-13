<?php

namespace DCS\NotificationBundle\Notifier\Collection;

use DCS\NotificationBundle\Model\ComponentInterface;
use DCS\NotificationBundle\Model\ComponentManagerInterface;

class RecipientCollection implements \IteratorAggregate, RecipientCollectionInterface
{
    /**
     * @var \ArrayIterator
     */
    protected $coll;

    /**
     * @var ComponentManagerInterface
     */
    protected $componentManager;

    function __construct(ComponentManagerInterface $componentManager)
    {
        $this->coll = new \ArrayIterator();
        $this->componentManager = $componentManager;
    }

    /**
     * {@inheritdoc}
     */
    public function add($model, $identifier)
    {
        $component = $this->componentManager->findOrCreateComponent($model, $identifier);
        $this->addComponent($component);
    }

    /**
     * {@inheritdoc}
     */
    public function addComponent(ComponentInterface $component)
    {
        if (!$this->contains($component)) {
            $this->coll[] = $component;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function contains(ComponentInterface $component)
    {
        return in_array($component, $this->coll->getArrayCopy(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->coll;
    }
} 