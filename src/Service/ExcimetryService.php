<?php

namespace Excimetry\ExcimetryBundle\Service;

use Excimetry\Excimetry;
use Excimetry\ExcimetryConfig;

/**
 * Service for the Excimetry profiler.
 */
class ExcimetryService
{
    /**
     * @var Excimetry The Excimetry instance
     */
    private Excimetry $excimetry;

    /**
     * Constructor.
     *
     * @param bool $enabled Whether the service is enabled
     * @param float $period The sampling period in seconds
     * @param string $mode The profiling mode (wall or cpu)
     */
    public function __construct(bool $enabled, float $period, string $mode)
    {
        // Create the Excimetry instance with the configuration
        $config = new ExcimetryConfig([
            'period' => $period,
            'mode' => $mode,
        ]);
        
        $this->excimetry = new Excimetry($config);
        
        // Start profiling if enabled
        if ($enabled) {
            $this->excimetry->start();
        }
    }

    /**
     * Get the Excimetry instance.
     *
     * @return Excimetry
     */
    public function getExcimetry(): Excimetry
    {
        return $this->excimetry;
    }

    /**
     * Start profiling.
     *
     * @return self
     */
    public function start(): self
    {
        $this->excimetry->start();
        return $this;
    }

    /**
     * Stop profiling.
     *
     * @return self
     */
    public function stop(): self
    {
        $this->excimetry->stop();
        return $this;
    }

    /**
     * Reset the profiler.
     *
     * @return self
     */
    public function reset(): self
    {
        $this->excimetry->reset();
        return $this;
    }

    /**
     * Check if the profiler is running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->excimetry->isRunning();
    }
}