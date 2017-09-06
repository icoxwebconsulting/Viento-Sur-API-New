<?php

namespace VientoSur\App\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VientoSurAppAppBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
