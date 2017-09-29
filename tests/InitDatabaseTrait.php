<?php
declare(strict_types=1);

namespace Tests;

use App;
use Nette;
use Nextras;
use Symfony;

trait InitDatabaseTrait
{
	private function initDatabaseStructure(Nette\DI\Container $container)
	{
		$recreateDatabase = $container->getParameters()['recreateDatabase'];

		/** @var App\Model\Migration\ContinueCommand $continueCommand */
		$continueCommand = $container->getByType(App\Model\Migration\ContinueCommand::class);
		/** @var Symfony\Component\Console\Application $symfonyConsole */
		$symfonyConsole = $container->getByType(Symfony\Component\Console\Application::class);

		$continueCommand->setApplication($symfonyConsole);
		$inputInterface = new Symfony\Component\Console\Input\ArrayInput(
			[
				'--dev'         => '',
				'--disable-php' => '',
			]
		);
		$outputInterface = new Symfony\Component\Console\Output\ConsoleOutput;

		if ($recreateDatabase) {
			$continueCommand->getDriver()->setupConnection();
			$continueCommand->getDriver()->emptyDatabase();

			/** @var Nextras\Migrations\Extensions\SqlHandler $sqlExtension */
			$sqlExtension = $continueCommand->getExtensionHandlers()['sql'];
			$structureFile = new Nextras\Migrations\Entities\File;
			$structureFile->path = implode(DIRECTORY_SEPARATOR, [
				testsDir,
				'database',
				'structure.sql',
			]);
			if (is_file($structureFile->path)) {
				$sqlExtension->execute($structureFile);
			}

			$dataFile = new Nextras\Migrations\Entities\File;
			$dataFile->path = implode(DIRECTORY_SEPARATOR, [
				testsDir,
				'database',
				'data.sql',
			]);
			if (is_file($dataFile->path)) {
				$sqlExtension->execute($dataFile);
			}
		}

		$continueCommand->run($inputInterface, $outputInterface);
	}
}
