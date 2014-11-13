<?php

namespace DCS\NotificationBundle\Resolver;

interface ResolverComponentModelIdentifierInterface
{
    /**
     * Get model
     *
     * @return string
     */
    public function getModel();

    /**
     * Get identifier
     *
     * @return mixed
     */
    public function getIdentifier();
} 