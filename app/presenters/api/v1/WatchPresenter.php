<?php

namespace App\ApiModule\Presenters;

use App;
use Drahak;
use Nette;

final class WatchPresenter extends BaseApiPresenter
{
	/**
	 * @var App\Model\Entity\Watch\WatchRepository @inject
	 */
	public $watchRepository;

	public function actionRead($id = NULL)
	{
		$this->resource->watches = is_null($id) ? $this->readAll() : $this->readOne($id);
		$this->sendResource(self::CONTENT_TYPE);
	}


	/**
	 * @SWG\Get(path="/watches",
	 *   tags={"watches"},
	 *   summary="Get watch details",
	 *   description="Lists all watches which meet the filter criteria.",
	 *   operationId="readAllWatches",
	 *   produces={"application/json"},
	 *   @SWG\Parameter(
	 *     in="query",
	 *     name="page",
	 *     description="Displayed page of the watch list. If the page is out of range, empty array will be returned.",
	 *     required=false,
	 *     type="integer"
	 *   ),
	 *   @SWG\Parameter(
	 *     in="query",
	 *     name="per_page",
	 *     description="Items per page. This parameter works only when 'page' parameter is also included.",
	 *     required=false,
	 *     type="integer"
	 *   ),
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be expected."),
	 *   @SWG\Response(response="400", description="One or more filter parameters are not in correct format.")
	 * )
	 */
	private function readAll()
	{
		try {
			// TODO validate query

			$data = [];
			foreach ($this->watchRepository->findByFilter($this->getHttpRequest()->getQuery()) as $watch) {
				$data[] = $this->serializeWatchData($watch);
			}
		}
		catch (App\Model\InvalidArgumentException $e) {
			$this->sendErrorResponse(new Drahak\Restful\Application\BadRequestException('Invalid parameter', Nette\Http\IResponse::S400_BAD_REQUEST, $e));
		}

		return $data;
	}


	/**
	 * @SWG\Get(path="/watches/{id}",
	 *   tags={"watches"},
	 *   summary="Get watch with given id",
	 *   description="Returns information about watch with given identifier.",
	 *   operationId="readOneWatch",
	 *   produces={"application/json"},
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be expected."),
	 *   @SWG\Response(response="404", description="Item with given identifier does not exist.")
	 * )
	 * @param $id
	 *
	 * @return array
	 */
	private function readOne(int $id)
	{
		$watch = NULL;
		try {
			$watch = $this->watchRepository->getById($id);
			return $this->serializeWatchData($watch);
		}
		catch (App\Model\Entity\Watch\WatchNotFoundException $e) {
			$this->sendErrorResponse(Drahak\Restful\Application\BadRequestException::notFound('Watch not found'));
		}

	}
}
