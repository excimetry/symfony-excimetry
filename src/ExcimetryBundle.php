<?php

namespace Excimetry\ExcimetryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * ExcimetryBundle class.
 */
class ExcimetryBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}