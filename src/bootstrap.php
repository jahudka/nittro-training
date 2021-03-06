<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;
$configurator->setDebugMode(true);

$rootDir = dirname(__DIR__);
$configurator->enableTracy($rootDir . '/var/log');
$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory($rootDir . '/var');
$configurator->addParameters([
    'rootDir' => $rootDir,
    'srcDir' => $rootDir . '/src',
    'wwwDir' => $rootDir . '/public',
]);

if (!is_file($rootDir . '/etc/config.local.neon')) {
    die("Please create and set up the local configuration file!\n");
}

$configurator->addConfig($rootDir . '/etc/config.neon');
$configurator->addConfig($rootDir . '/etc/config.local.neon');

return $configurator->createContainer();
