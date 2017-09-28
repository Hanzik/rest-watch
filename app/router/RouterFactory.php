<?php

namespace App\Router;

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
		$this->setupApiRoute();
	}

	private function setupApiRoute()
	{
		/** Info resources */
		$this[] = new Restful\Application\Routes\CrudRoute($this->rootPath . 'api/v1/info', 'Info', Restful\Application\IResourceRouter::GET);

		$this[] = new Restful\Application\Routes\CrudRoute($this->rootPath . 'api/v1/watch[/<id>[/<relation>]]', 'Watch', Restful\Application\IResourceRouter::GET);
		$this[] = new Restful\Application\Routes\CrudRoute($this->rootPath . 'api/v1/watch', 'Watch', Restful\Application\IResourceRouter::POST);
		$this[] = new Restful\Application\Routes\CrudRoute($this->rootPath . 'api/v1/watch/<id>', 'Watch', Restful\Application\IResourceRouter::DELETE);
	}
}
