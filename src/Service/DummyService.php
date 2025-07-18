<?php

namespace Excimetry\ExcimetryBundle\Service;

/**
 * Example service for the ExcimetryBundle.
 */
class DummyService
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * Constructor.
     *
     * @param bool $enabled Whether the service is enabled
     */
    public function __construct(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Check if the service is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Example method.
     *
     * @return string
     */
    public function getHello(): string
    {
        return 'Hello from ExcimetryBundle!';
    }
}