<?php

namespace App\Model\Restful;

use Drahak;
use Nette;

class MethodHandler extends Drahak\Restful\Application\Events\MethodHandler
{
	/**
	 * @var string
	 */
	private $urlPrefix;
	/**
	 * @var Nette\Http\IRequest
	 */
	private $request;

	/**
	 * @param string                                   $urlPrefix
	 * @param Nette\Http\IRequest                      $request
	 * @param Nette\Http\IResponse                     $response
	 * @param Drahak\Restful\Application\MethodOptions $methods
	 */
	public function __construct(
		$urlPrefix,
		Nette\Http\IRequest $request,
		Nette\Http\IResponse $response,
		Drahak\Restful\Application\MethodOptions $methods
	) {
		parent::__construct($request, $response, $methods);

		$this->urlPrefix = $urlPrefix;
		$this->request = $request;
	}

	public function run(Nette\Application\Application $application)
	{
		if (Nette\Utils\Strings::startsWith($this->request->getUrl()->getPath(), $this->urlPrefix)) {
			parent::run($application);
		}
	}
}
