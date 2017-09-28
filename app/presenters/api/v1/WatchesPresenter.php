<?php

namespace App\ApiModule\Presenters;

use App;
use Drahak;
use Nette;

final class WatchesPresenter extends BaseApiPresenter
{
	/**
	 * @var App\Model\Entity\Watch\WatchRepository @inject
	 */
	public $watchRepository;

	public function actionRead($id = NULL)
	{
		$this->resource = is_null($id) ? $this->readAll() : $this->readOne($id);
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
			$query = $this->getHttpRequest()->getQuery();
			$page = $this->getHttpRequest()->getQuery('page', self::DEFAULT_PAGE);
			$pageSize = $this->getHttpRequest()->getQuery('page', self::DEFAULT_PAGE_SIZE);
			// TODO validate query

			$data = [];
			foreach ($this->watchRepository->findByFilter($query, $page, $pageSize) as $watch) {
				$data[] = $watch->serialize();
			}
			return $data;
		}
		catch (App\Model\InvalidArgumentException $e) {
			$this->sendErrorResponse(new Drahak\Restful\Application\BadRequestException('Invalid filter parameter', Nette\Http\IResponse::S400_BAD_REQUEST, $e));
		}
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
		try {
			return $this->watchRepository->getById($id)->serialize();
		}
		catch (App\Model\Entity\Watch\WatchNotFoundException $e) {
			$this->sendErrorResponse(Drahak\Restful\Application\BadRequestException::notFound('Watch not found'));
		}
	}
}
