<?php

namespace MyVendor\ExcimetryBundle\Tests\DependencyInjection;

use Excimetry\ExcimetryBundle\DependencyInjection\ExcimetryExtension;
use Excimetry\ExcimetryBundle\Service\DummyService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExcimetryExtensionTest extends TestCase
{
    private ExcimetryExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new ExcimetryExtension();
        $this->container = new ContainerBuilder();
    }

    public function testServiceRegistration(): void
    {
        // Load the extension with default configuration
        $this->extension->load([], $this->container);

        // Compile the container to resolve all service definitions
        $this->container->compile();

        // Check that DummyService is registered
        $this->assertTrue($this->container->has(DummyService::class));

        // Get the service and check it's the correct class
        $service = $this->container->get(DummyService::class);
        $this->assertInstanceOf(DummyService::class, $service);
    }

    public function testServiceConfiguration(): void
    {
        // Load the extension with custom configuration
        $this->extension->load([
            'excimetry' => [
                'enabled' => false,
            ],
        ], $this->container);

        // Compile the container
        $this->container->compile();

        // Check that the parameter was set correctly
        $this->assertFalse($this->container->getParameter('excimetry.enabled'));

        // Get the service and check its configuration
        $service = $this->container->get(DummyService::class);
        $this->assertFalse($service->isEnabled());
    }

    public function testExtensionAlias(): void
    {
        // Check that the extension alias is set correctly
        $this->assertEquals('excimetry', $this->extension->getAlias());
    }
}
