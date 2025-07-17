<?php

namespace MyVendor\ExcimetryBundle\Tests\DependencyInjection;

use Excimetry\ExcimetryBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    private Configuration $configuration;
    private Processor $processor;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testDefaultConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [[]]);

        $this->assertTrue($config['enabled']);
        $this->assertEquals(0.01, $config['period']);
        $this->assertEquals('wall', $config['mode']);
    }

    public function testCustomConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [[
            'enabled' => false,
            'period' => 0.5,
            'mode' => 'cpu',
        ]]);

        $this->assertFalse($config['enabled']);
        $this->assertEquals(0.5, $config['period']);
        $this->assertEquals('cpu', $config['mode']);
    }

    public function testInvalidMode(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [[
            'mode' => 'invalid',
        ]]);
    }

    public function testInvalidPeriod(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [[
            'period' => 'not-a-float',
        ]]);
    }

    public function testInvalidEnabled(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [[
            'enabled' => 'not-a-boolean',
        ]]);
    }
}