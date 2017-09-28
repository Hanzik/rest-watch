<?php

namespace App\ApiModule\Presenters;

use App;
use Drahak;

/**
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host=API_HOST,
 *     basePath="/api/v1",
 *     @SWG\Info(
 *         version="1.0",
 *         title="Rest Watch",
 *         description="Rest Watch API provides simple access to .",
 *         @SWG\Contact(
 *             email="h.vratnik@email.cz"
 *         ),
 *     )
 * )
 */
abstract class BaseApiPresenter extends Drahak\Restful\Application\UI\ResourcePresenter
{
	const VERSION = '1.0';

	const DEFAULT_PAGE = 0;
	const DEFAULT_PAGE_SIZE = 20;

	/** Format of response data - JSON and XML should be supported. */
	const CONTENT_TYPE = Drahak\Restful\IResource::JSON;

	public function startup()
	{
		parent::startup();
	}

	protected function sendErrorResponse($e)
	{
		$this->sendErrorResource($e, self::CONTENT_TYPE);
	}
}
