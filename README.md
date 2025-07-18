# ExcimetryBundle

A Symfony bundle for integrating [Excimetry](https://github.com/excimetry/excimetry) with your Symfony application.

<p align="center">
<a href="https://github.com/excimetry/symfony-excimetry/actions"><img src="https://github.com/excimetry/symfony-excimetry/actions/workflows/tests.yml/badge.svg" alt="Tests status"></a>
<a href="https://packagist.org/packages/excimetry/symfony-excimetry"><img src="https://img.shields.io/packagist/v/excimetry/symfony-excimetry.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/excimetry/symfony-excimetry"><img src="https://img.shields.io/packagist/l/excimetry/symfony-excimetry.svg" alt="License"></a>
</p>

## Requirements

- PHP 8.2 or higher
- Symfony 6.4 or higher
- excimetry/excimetry package

## Installation

### Step 1: Install the Bundle

```bash
composer require excimetry/symfony-excimetry
```

> **Note**: If you're using Symfony Flex (which is the case for most modern Symfony applications), the bundle will be automatically registered in your application's `config/bundles.php` file. No additional configuration is needed for the bundle registration.

### Step 2: Configure the Bundle

The installation process automatically creates configuration files at `config/packages/excimetry.yaml` or `config/packages/excimetry.php`. You can use either format based on your preference. If these files don't exist, you can create them manually:

#### YAML Configuration (config/packages/excimetry.yaml)

```yaml
# config/packages/excimetry.yaml
excimetry:
  # Whether the bundle is enabled
  enabled: true

  # The sampling period in seconds
  period: 0.01

  # The profiling mode (wall or cpu)
  mode: wall
```

#### PHP Configuration (config/packages/excimetry.php)

```php
<?php
// config/packages/excimetry.php
return [
    'excimetry' => [
        // Whether the bundle is enabled
        'enabled' => true,

        // The sampling period in seconds
        'period' => 0.01,

        // The profiling mode (wall or cpu)
        'mode' => 'wall',
    ],
];
```

## Usage

### Using the ExcimetryService

The bundle provides an `ExcimetryService` that wraps the Excimetry profiler. You can use it as follows:

You don't need to call `->start()` method if you enabled the bundle.

```php
<?php
class Kernel extends BaseKernel
{
   ... 
   
    private ?ExcimetryService $excimetryService;

    public function boot(): void
    {
        if (!$this->booted) {
            // Start the profiler before booting the kernel
            if ($this->container && $this->container->has(ExcimetryService::class)) {
                $this->excimetryService = $this->container->get(ExcimetryService::class);
            }

           ...
        }
    }

    public function terminate(Request $request, Response $response): void
    {
        if (!is_null($this->excimetryService)) {
            $log = $this
                ->excimetryService
                ->stop()
                ->getExcimetry()
                ->getLog();
            
            $exporter = new PyroscopeBackend(
                serverUrl: 'https://pyro:4040',
                appName: 'symfony_app',
                exporter: new CollapsedExporter()
            );
            
            $exporter->send($log);
        }

        ...
    }
}

```

## License

This project is licensed under the Apache License 2.0 - see the LICENSE file for details.
