<?php

namespace App\ApiModule\Presenters;

use App;
use Drahak;
use Nette\Http\IRequest;

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

	/**
	 * @var App\Model\Decoder\IDecoder
	 */
	private $decoder;
	/**
	 * @var array
	 */
	protected $requestBody;

	public function injectDecoder(App\Model\Decoder\IDecoder $decoder)
	{
		$this->decoder = $decoder;
	}

	public function startup()
	{
		parent::startup();

		if (in_array($this->getHttpRequest()->getMethod(), [IRequest::POST, IRequest::PUT])) {
			$this->requestBody = $this->decoder->decode(file_get_contents('php://input'));
		}
	}

	protected function sendErrorResponse($e)
	{
		$this->sendErrorResource($e, self::CONTENT_TYPE);
	}
}
