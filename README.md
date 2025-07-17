# ExcimetryBundle

A Symfony bundle for integrating Excimetry with your Symfony application.

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

```php
<?php

namespace App\Controller;

use Excimetry\ExcimetryBundle\Service\ExcimetryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    #[Route('/example', name: 'app_example')]
    public function index(ExcimetryService $excimetryService): Response
    {
        // Start profiling (if not already started)
        $excimetryService->start();

        // Your code to profile
        $result = $this->doSomething();

        // Stop profiling
        $excimetryService->stop();

        // Get the Excimetry instance for more advanced operations
        $excimetry = $excimetryService->getExcimetry();

        // Save the profile to a file
        $excimetry->save();

        return $this->render('example/index.html.twig', [
            'result' => $result,
        ]);
    }

    private function doSomething(): string
    {
        // Some code to profile
        return 'Hello from ExcimetryBundle!';
    }
}
```

## Testing

The bundle comes with a comprehensive test suite. To run the tests, you need to have PHPUnit installed.

### Running Tests

You can run the tests using the PHPUnit configuration provided:

```bash
# Run all tests
vendor/bin/phpunit

# Run a specific test suite
vendor/bin/phpunit --testsuite "ExcimetryBundle Test Suite"

# Run a specific test file
vendor/bin/phpunit tests/DependencyInjection/ConfigurationTest.php

# Run with code coverage report
vendor/bin/phpunit --coverage-html coverage-report
```

### Test Structure

The tests are organized into the following directories:

- `tests/DependencyInjection/`: Tests for the bundle's dependency injection configuration
- `tests/Functional/`: Functional tests that test the bundle in a real Symfony application
- `tests/`: Other tests, including the installer test

## License

This project is licensed under the Apache License 2.0 - see the LICENSE file for details.
