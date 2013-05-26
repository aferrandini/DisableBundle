<?php

namespace Ferrandini\Bundle\DisableBundle\Tests\Annotations\Fixtures\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

/**
 * @Disable()
 */
class DisabledController
{
    public function indexAction()
    {

    }
}
