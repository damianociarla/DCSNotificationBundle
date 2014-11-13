<?php

namespace DCS\NotificationBundle\Resolver;

class ResolverComponentModelIdentifier implements ResolverComponentModelIdentifierInterface
{
    private $model;

    private $identifier;

    function __construct($model, $identifier)
    {
        if (is_object($model)) {
            $model = get_class($model);
        }

        $this->model = $model;

        if (is_scalar($identifier)) {
            $identifier = (string)$identifier;
        }

        return $this->identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}