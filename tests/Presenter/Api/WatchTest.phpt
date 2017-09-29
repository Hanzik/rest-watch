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
class WatchTest extends Tester\TestCase
{
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
		$this->presenter = new App\ApiModule\Presenters\WatchesPresenter;

		$this->presenter->watchRepository = $this->container->getByType(App\Model\Entity\Watch\WatchRepository::class);

		$this->presenter->injectDrahakRestful(
			$this->container->getByType('Drahak\Restful\Application\IResponseFactory'),
			$this->container->getByType('Drahak\Restful\IResourceFactory'),
			$this->container->getByType('Drahak\Restful\Security\AuthenticationContext'),
			$this->container->getByType('Drahak\Restful\Http\InputFactory'),
			$this->container->getByType('Drahak\Restful\Utils\RequestFilter')
		);

		$this->populateDB();

		$this->presenter->autoCanonicalize = FALSE;
		$this->presenter->invalidLinkMode = 1;
	}

	private function populateDB()
	{
		$populationSize = 10;
		for ($i = 0; $i < $populationSize; $i++) {

			$watch = new App\Model\DTO\WatchDTO(
				[
					'title'       => 'Test watch ' . $i,
					'price'       => 10000 + $i,
					'description' => 'Description of test watch'
				]
			);

			$fountainData = [];
			if ($i % 2 === 0) {
				$fountainData = "BASE64IMAGEREPRESENTEDASSTRING";
			}
			else {
				$fountainData = [
					'color'  => 'red',
					'height' => $i . 'cm'
				];
			}
			$fountain = new App\Model\DTO\FountainDTO($fountainData);

			$this->presenter->watchRepository->create($watch, $fountain);
		}
	}

	public function testCreateItem()
	{
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::POST, [
			'action' => 'create',
		]);

		$applicationResponse = $this->makeRequest($request,
			'{
				"title": "Created test watch",
				"price": "9001",
				"description": "Description of created test watch",
				"fountain": {
					"color": "blue",
					"height": "20cm"
				}
			}'
		);

		$createdItem = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(201, $response->getCode());
		$this->assertNonEmptyData($createdItem);
		$this->assertWatch($createdItem);
	}

	public function testCreateInvalidItem()
	{
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::POST, [
			'action' => 'create',
		]);

		$this->makeRequest($request,
			'{
				"title": "Created test watch",
				"price": "Not a real price",
				"description": "Description of created test watch"
			}'
		);

		$response = $this->presenter->getHttpResponse();

		Assert::same(400, $response->getCode());
	}

	public function testUpdateItem()
	{
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::POST, [
			'action' => 'update',
			'id'     => 1
		]);

		$applicationResponse = $this->makeRequest($request,
			'{
				"title": "Updated test watch",
				"price": 12345,
				"description": "Description of updated test watch",
				"fountain": {
					"color": "green",
					"height": "20cm"
				}
			}'
		);

		$createdItem = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(201, $response->getCode());
		$this->assertNonEmptyData($createdItem);
		$this->assertWatch($createdItem);
	}

	/**
	 * Update an item with invalid price
	 */
	public function testUpdateInvalidItem()
	{
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::POST, [
			'action' => 'update',
			'id'     => 1
		]);

		$this->makeRequest($request,
			'{
				"price": "Not a real price",
			}'
		);

		$response = $this->presenter->getHttpResponse();

		Assert::same(400, $response->getCode());
	}

	/**
	 * Ask for a page of watches and expect to have some results in correct format returned.
	 */
	public function testListAllItems()
	{
		Assert::true(TRUE);
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::GET, [
			'action' => 'read',
		]);

		$applicationResponse = $this->makeRequest($request);

		$data = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(200, $response->getCode());
		$this->assertNonEmptyData($data);
		$items = $data['items'];
		$this->assertNonEmptyData($items);
		$this->assertWatch(reset($items));
	}

	/**
	 * Ask for a page of watches whose title contains watch and expect to have some results in correct format returned.
	 */
	public function testListFilterItems()
	{
		Assert::true(TRUE);
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::GET, [
			'action' => 'read',
		]);

		$applicationResponse = $this->makeRequest($request, NULL, '?title=watch');

		$data = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(200, $response->getCode());
		$this->assertNonEmptyData($data);
		$items = $data['items'];
		$this->assertNonEmptyData($items);
		$this->assertWatch(reset($items));
	}

	/**
	 * Ask for a watch with specific ID, this should a single result.
	 */
	public function testGetSingleItem()
	{
		Assert::true(TRUE);
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::GET, [
			'action' => 'read',
			'id'     => 1
		]);

		$applicationResponse = $this->makeRequest($request);

		$item = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(200, $response->getCode());
		$this->assertNonEmptyData($item);
		$this->assertWatch($item);
	}

	/**
	 * Ask for a watch with specific ID which does not exist.
	 */
	public function testGetInvalidSingleItem()
	{
		Assert::true(TRUE);
		$request = new Nette\Application\Request(static::PRESENTER, Nette\Http\IRequest::GET, [
			'action' => 'read',
			'id'     => 10000
		]);

		$applicationResponse = $this->makeRequest($request);

		$item = $applicationResponse->getData();
		$response = $this->presenter->getHttpResponse();

		Assert::same(404, $response->getCode());
	}

	private function assertNonEmptyData($data)
	{
		Assert::true(!is_null($data) && count($data) > 0);
	}

	private function assertWatch($item)
	{
		Assert::true(array_key_exists('title', $item));
		Assert::true(array_key_exists('price', $item));
		Assert::true(array_key_exists('description', $item));
		Assert::true(array_key_exists('fountain', $item));
	}

	/**
	 * @param Nette\Application\Request $request
	 * @param string                    $url
	 *
	 * @return Drahak\Restful\Application\Responses\BaseResponse
	 */
	private function makeRequest(Nette\Application\Request $request, $body = NULL, $url = '')
	{
		$urlScript = new Nette\Http\UrlScript('http://localhost' . $url);
		$httpRequest = new Nette\Http\Request($urlScript,
			NULL, NULL, NULL, NULL,
			NULL, NULL, NULL, NULL,
			is_null($body) ?
				NULL :
				function () use ($body) {
					return $body;
				}
		);

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

$test = new WatchTest($container);
$test->run();
