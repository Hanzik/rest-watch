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
class WatchResourceTest extends Tester\TestCase
{
	use Tests\InitDatabaseTrait;

	const PRESENTER = 'Api:Watches';

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var App\ApiModule\Presenters\WatchesPresenter
	 */
	private $presenter;

	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	protected function setUp()
	{
		Tester\Environment::lock('database', testsDir . '/temp');
		$this->initDatabaseStructure($this->container);

		$this->presenter = new App\ApiModule\Presenters\WatchesPresenter;

		$this->presenter->watchRepository = $this->container->getByType(App\Model\Entity\Watch\WatchRepository::class);

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

	public function testListAllItems()
	{
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::GET, [
			'action' => 'read',
		]);

		$applicationResponse = $this->makeRequest($request);

		$data = $applicationResponse->getData();
		Assert::true(count($data) > 0);
	}

	/**
	 * @param Nette\Application\Request $request
	 * @param string                    $url
	 *
	 * @return Drahak\Restful\Application\Responses\BaseResponse
	 */
	private function makeRequest(Nette\Application\Request $request, $url = '')
	{
		$urlScript = new Nette\Http\UrlScript('http://localhost' . $url);
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

$test = new WatchResourceTest($container);
$test->run();
