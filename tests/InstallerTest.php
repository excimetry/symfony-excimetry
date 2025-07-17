<?php

namespace MyVendor\ExcimetryBundle\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Excimetry\ExcimetryBundle\Installer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class InstallerTest extends TestCase
{
    private string $tempDir;
    private string $configDir;
    private string $vendorDir;
    private string $projectDir;
    private Event $event;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        // Create a temporary directory structure
        $this->tempDir = sys_get_temp_dir() . '/excimetry_test_' . uniqid();
        $this->projectDir = $this->tempDir . '/project';
        $this->vendorDir = $this->projectDir . '/vendor';
        $this->configDir = $this->projectDir . '/config/packages';
        
        // Create the directory structure
        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir([
            $this->projectDir,
            $this->vendorDir,
        ]);
        
        // Mock the Composer event
        $composer = $this->createMock(Composer::class);
        $config = $this->createMock(Config::class);
        $io = $this->createMock(IOInterface::class);
        
        $config->method('get')->with('vendor-dir')->willReturn($this->vendorDir);
        $composer->method('getConfig')->willReturn($config);
        
        $this->event = $this->createMock(Event::class);
        $this->event->method('getComposer')->willReturn($composer);
        $this->event->method('getIO')->willReturn($io);
    }

    protected function tearDown(): void
    {
        // Clean up the temporary directory
        if (is_dir($this->tempDir)) {
            $this->filesystem->remove($this->tempDir);
        }
    }

    public function testPostInstallCreatesConfigFile(): void
    {
        // Create the source file that will be copied
        $bundleDir = dirname(__DIR__);
        $sourceFile = $bundleDir . '/config/excimetry.yaml.dist';
        
        // Ensure the source file exists
        $this->assertTrue(file_exists($sourceFile), 'Source config file does not exist');
        
        // Run the installer
        Installer::postInstall($this->event);
        
        // Check that the config directory was created
        $this->assertTrue(is_dir($this->configDir), 'Config directory was not created');
        
        // Check that the config file was copied
        $targetFile = $this->configDir . '/excimetry.yaml';
        $this->assertTrue(file_exists($targetFile), 'Config file was not copied');
        
        // Check the content of the copied file
        $sourceContent = file_get_contents($sourceFile);
        $targetContent = file_get_contents($targetFile);
        $this->assertEquals($sourceContent, $targetContent, 'Config file content does not match');
    }

    public function testPostInstallDoesNotOverwriteExistingFile(): void
    {
        // Create the config directory and a custom config file
        $this->filesystem->mkdir($this->configDir);
        $targetFile = $this->configDir . '/excimetry.yaml';
        $customContent = 'custom: configuration';
        file_put_contents($targetFile, $customContent);
        
        // Run the installer
        Installer::postInstall($this->event);
        
        // Check that the existing file was not overwritten
        $this->assertEquals($customContent, file_get_contents($targetFile), 'Existing config file was overwritten');
    }
}