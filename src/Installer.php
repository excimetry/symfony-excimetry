<?php

namespace Excimetry\ExcimetryBundle;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Installer class for the ExcimetryBundle.
 */
class Installer
{
    /**
     * Post-install/update script to copy the configuration file.
     *
     * @param Event $event The composer event
     */
    public static function postInstall(Event $event)
    {
        $io = $event->getIO();

        // Ask for confirmation before executing post-scripts
        if (!$io->askConfirmation('<question>Do you want to execute post-install scripts and copy configuration files? (y/n) </question>', true)) {
            $io->write('<info>Post-install scripts skipped.</info>');
            return;
        }

        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $projectDir = dirname($vendorDir);

        $configDir = $projectDir . '/config/packages';
        $filesystem = new Filesystem();

        // Create the config directory if it doesn't exist
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        // Copy YAML configuration file if it doesn't exist
        $yamlSourceFile = __DIR__ . '/../config/excimetry.yaml.dist';
        $yamlTargetFile = $configDir . '/excimetry.yaml';

        if (!file_exists($yamlTargetFile)) {
            $filesystem->copy($yamlSourceFile, $yamlTargetFile);
            $io->write('<info>Created YAML config file: config/packages/excimetry.yaml</info>');
        }

        // Copy PHP configuration file if it doesn't exist
        $phpSourceFile = __DIR__ . '/../config/excimetry.php.dist';
        $phpTargetFile = $configDir . '/excimetry.php';

        if (!file_exists($phpTargetFile)) {
            $filesystem->copy($phpSourceFile, $phpTargetFile);
            $io->write('<info>Created PHP config file: config/packages/excimetry.php</info>');
        }
    }
}
