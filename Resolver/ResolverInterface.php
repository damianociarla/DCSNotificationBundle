<?php

namespace DCS\NotificationBundle\Resolver;

interface ResolverInterface
{
    public function resolve(ResolverComponentModelIdentifierInterface $resolverComponent);
} 