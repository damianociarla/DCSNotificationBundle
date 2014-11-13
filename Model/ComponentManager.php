<?php

namespace DCS\NotificationBundle\Model;

use DCS\NotificationBundle\DCSNotificationEvents;
use DCS\NotificationBundle\Event\ComponentEvent;
use DCS\NotificationBundle\Resolver\ResolverComponent;
use DCS\NotificationBundle\Resolver\ResolverComponentModelIdentifier;
use DCS\NotificationBundle\Resolver\ResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ComponentManager implements ComponentManagerInterface
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    function __construct(ResolverInterface $resolver, EventDispatcherInterface $dispatcher)
    {
        $this->resolver = $resolver;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createComponent()
    {
        $class = $this->getClass();
        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    public function save(ComponentInterface $component)
    {
        $this->doSave($component);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrCreateComponent($model, $identifier)
    {
        $resolverComponent = new ResolverComponentModelIdentifier($model, $identifier);

        $hash = $this->createHash(
            $resolverComponent->getModel(),
            $resolverComponent->getIdentifier()
        );

        if (null === $component = $this->findByHash($hash)) {
            $component = $this->createComponent();
            $component->setModel($resolverComponent->getModel());
            $component->setIdentifier($resolverComponent->getIdentifier());
            $component->setHash($hash);
            $component->setData($this->resolver->resolve($resolverComponent));

            $this->save($component);

            $this->dispatcher->dispatch(DCSNotificationEvents::COMPONENT_CREATED, new ComponentEvent($component));
        }

        return $component;
    }

    /**
     * {@inheritdoc}
     */
    public function findByHash($hash)
    {
        return $this->findOneBy(array(
            'hash' => $hash
        ));
    }

    /**
     * Create hash from model and identifier
     *
     * @param string $model
     * @param mixed $identifier
     * @return string
     * @throw \Exception
     */
    protected function createHash($model, $identifier)
    {
        if (!is_scalar($identifier) && !is_array($identifier)) {
            throw new \Exception('The identifier must be a scalar value or array value');
        }
        return $model.'#'.serialize($identifier);
    }

    /**
     * Find ComponentInterface by criteria
     *
     * @param array $criteria
     * @return ComponentInterface|null
     */
    abstract protected function findOneBy(array $criteria);

    /**
     * Performs the persistence of the ComponentInterface
     *
     * @param ComponentInterface $component
     * @return void
     */
    abstract protected function doSave(ComponentInterface $component);
} 