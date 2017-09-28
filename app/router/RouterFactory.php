<?php

namespace App\Router;

use App;
use Drahak\Restful;
use Nette;

class RouterFactory extends Nette\Application\Routers\RouteList
{
	/**
	 * @var string
	 */
	private $rootPath;

	public function __construct($rootPath = '')
	{
		parent::__construct();

		$this->rootPath = $rootPath;
		$this[] = new Nette\Application\Routers\Route($rootPath . '[/<presenter>[/<action>[/<id>]]]', 'Homepage:default');
		$this->setupApiRoute();
	}

	private function setupApiRoute()
	{
		/** Info resources */
		$this[] = new Restful\Application\Routes\CrudRoute($this->rootPath . '/api/v1/info', 'Info', Restful\Application\IResourceRouter::GET);

		$this[] = $api = new App\Model\PrefixedRouteList('/api', 'Api');

		$api[] = new Restful\Application\Routes\CrudRoute($this->rootPath . '/api/v1/watches[/<id>]', 'Watches', Restful\Application\IResourceRouter::GET);
		$api[] = new Restful\Application\Routes\CrudRoute($this->rootPath . '/api/v1/watches', 'Watches', Restful\Application\IResourceRouter::POST);
		$api[] = new Restful\Application\Routes\CrudRoute($this->rootPath . '/api/v1/watches/<id>', 'Watches', Restful\Application\IResourceRouter::DELETE);
	}
}
