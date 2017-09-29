<?php
declare(strict_types=1);

namespace Tests\Presenter\Api;

use App;
use Nette;
use Drahak;
use Symfony;
use Tests;
use Tester;
use Tester\Assert;

$container = require dirname(dirname(__DIR__)) . '/bootstrap.php';

/**
 * @testCase
 */
class InfoTest extends Tester\TestCase
{
	use Tests\InitDatabaseTrait;

	const PRESENTER = 'Api:Info';

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var App\ApiModule\Presenters\InfoPresenter
	 */
	private $presenter;

	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	function setUp()
	{
		Tester\Environment::lock('database', testsDir . '/temp');
		$this->initDatabaseStructure($this->container);

		$this->presenter = new App\ApiModule\Presenters\InfoPresenter;

		$this->presenter->injectDrahakRestful(
			$this->container->getByType('Drahak\Restful\Application\IResponseFactory'),
			$this->container->getByType('Drahak\Restful\IResourceFactory'),
			$this->container->getByType('Drahak\Restful\Security\AuthenticationContext'),
			$this->container->getByType('Drahak\Restful\Http\InputFactory'),
			$this->container->getByType('Drahak\Restful\Utils\RequestFilter')
		);

		$this->presenter->autoCanonicalize = FALSE;
		$this->presenter->invalidLinkMode = 1;
	}

	/**
	 * @param Nette\Application\Request $request
	 * @param string                    $url
	 *
	 * @return Drahak\Restful\Application\Responses\ErrorResponse
	 */
	private function makeRequest(Nette\Application\Request $request, $url = '')
	{
		$urlScript = new Nette\Http\UrlScript('https://localhost' . $url);
		$httpRequest = new Nette\Http\Request($urlScript);

		$this->presenter->injectPrimary(
			NULL,
			NULL,
			NULL,
			$httpRequest,
			new Nette\Http\Response
		);

		return $this->presenter->run($request);
	}
}

$test = new InfoTest($container);
$test->run();
