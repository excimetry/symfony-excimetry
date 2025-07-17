<?php

namespace MyVendor\ExcimetryBundle\Tests\Functional;

use Excimetry\ExcimetryBundle\ExcimetryBundle;
use Excimetry\ExcimetryBundle\Service\DummyService;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class BundleIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        self::ensureKernelShutdown();
    }

    /**
     * @runInSeparateProcess
     */
    public function testBundleBoots(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        // Check that the bundle registered the service
        $this->assertTrue($container->has(DummyService::class));

        // Get the service and check it's the correct class
        $service = $container->get(DummyService::class);
        $this->assertInstanceOf(DummyService::class, $service);

        // Check that the service is configured correctly
        $this->assertTrue($service->isEnabled());
        $this->assertEquals('Hello from ExcimetryBundle!', $service->getHello());
    }
}

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new ExcimetryBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        // Configure the framework bundle
        $container->loadFromExtension('framework', [
            'test' => true,
            'router' => ['utf8' => true],
        ]);

        // Configure the excimetry bundle
        $container->loadFromExtension('excimetry', [
            'enabled' => true,
            'period' => 0.01,
            'mode' => 'wall',
        ]);
    }

    protected function configureRoutes($routes): void
    {
        // No routes needed for this test
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/logs';
    }
}
