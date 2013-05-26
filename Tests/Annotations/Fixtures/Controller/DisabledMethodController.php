<?php

namespace Ferrandini\Bundle\DisableBundle\Tests\Annotations\Fixtures\Controller;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

class DisabledMethodController
{

    /**
     * @Disable()
     */
    public function disabledAction()
    {

    }
}
