<?php

namespace App\Model;

use App;
use Nette;

class PrefixedRouteList extends Nette\Application\Routers\RouteList
{
	/**
	 * @var null
	 */
	private $prefix;

	/**
	 * PrefixedRouteList constructor.
	 *
	 * @param string $prefix
	 * @param string $module
	 */
	public function __construct($prefix, ?string $module)
	{
		parent::__construct($module);

		$this->prefix = $prefix;
	}

	/**
	 * Maps HTTP request to a Request object.
	 *
	 * @param Nette\Http\IRequest $httpRequest
	 *
	 * @return Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{
		if (Nette\Utils\Strings::startsWith($httpRequest->getUrl()->getPath(), $this->prefix)) {
			return parent::match($httpRequest);
		}

		return NULL;
	}
}
