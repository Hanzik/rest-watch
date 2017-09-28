<?php

define('rootDir', dirname(__DIR__));
require rootDir . '/vendor/autoload.php';

$logDirectory = rootDir . '/log';
$tempDirectory = rootDir . '/temp';

// Create directories if missing
if ( !file_exists($logDirectory)) {
	mkdir($logDirectory, 0770);
}
if ( !file_exists($tempDirectory)) {
	mkdir($tempDirectory, 0770);
}

$configurator = new Nette\Configurator;

$configurator->setDebugMode(TRUE);
$configurator->enableDebugger($logDirectory);
$configurator->setTempDirectory($tempDirectory);
$configurator->setTimeZone('Europe/Prague');

$configurator->createRobotLoader()
			 ->addDirectory(__DIR__)
			 ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
