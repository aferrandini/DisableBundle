<?php

namespace Ferrandini\Bundle\DisableBundle\Annotations;

use Symfony\Component\Routing\RouterInterface;

interface DisableInterface
{
    /**
     * @param RouterInterface $router
     * @return callable|null
     */
    public function getResponse(RouterInterface $router);
}