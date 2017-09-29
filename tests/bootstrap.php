<?php
declare(strict_types=1);

define('rootDir', dirname(__DIR__));
define('testsDir', __DIR__);

require rootDir . '/vendor/autoload.php';

if ( !class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

umask(7);

$debugRun = file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'localhost');

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$temp = __DIR__ . '/temp';
@mkdir($temp);

if ($debugRun) {
	$tempDir = $temp;
	Tracy\Debugger::$logDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'log';
}
else {
	$tempDir = $temp . DIRECTORY_SEPARATOR . getmypid();
	Tester\Helpers::purge($tempDir);
	$configurator->enableDebugger(__DIR__ . DIRECTORY_SEPARATOR . 'log');
}
$configurator->setTempDirectory($tempDir);

$configurator->createRobotLoader()
			 ->addDirectory(rootDir . '/app')
			 ->addDirectory(__DIR__)
			 ->register();

$configurator->addConfig(rootDir . '/app/config/config.neon');
$configurator->addParameters(
	[
		'recreateDatabase' => !$debugRun,
		'appDir'           => implode(DIRECTORY_SEPARATOR, [rootDir, 'app']),
		'ENV'              => array_filter($_ENV, function ($key) {
			return Nette\Utils\Strings::startsWith($key, 'MYSQL_');
		}, ARRAY_FILTER_USE_KEY),
		'sslAuthorizedDn'  => [],
	]
);
$configurator->addConfig(__DIR__ . '/config.tests.neon');
$configurator->addConfig(__DIR__ . '/config.tests.local.neon');

$container = $configurator->createContainer();

$sysTempDir = $tempDir . DIRECTORY_SEPARATOR . 'sysTemp';

@mkdir($sysTempDir, 0775, TRUE);

putenv('TMPDIR=' . $sysTempDir);

return $container;
