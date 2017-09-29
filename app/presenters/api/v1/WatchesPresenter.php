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

	/**
	 * @SWG\Post(path="/watches",
	 *   tags={"watches"},
	 *   summary="Creates a new watch",
	 *   description="Creates a new watch with parameters included in the query.",
	 *   operationId="createWatch",
	 *   produces={"application/json"},
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="title",
	 *     description="Watch title (required)",
	 *     required=true,
	 *     type="string"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="price",
	 *     description="Watch integer price (required)",
	 *     required=true,
	 *     type="integer"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="description",
	 *     description="Watch description (required)",
	 *     required=true,
	 *     type="string"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="fountain",
	 *     description="Fountain - either an object with parameters color and height or a string (image in base64)",
	 *     required=true,
	 *     type="object"
	 *   ),
	 *   @SWG\Response(response="201", description="All necessary fields were provided and watch was successfully
	 *                                 created."),
	 *   @SWG\Response(response="400", description="Malformed syntax or some required parameters were not provided.")
	 * )
	 */
	public function actionCreate()
	{
		try {
			$watchDto = new App\Model\DTO\WatchDTO($this->requestBody);

			$watch = $this->watchRepository->create($watchDto, $watchDto->getFountainDto());

			$this->getHttpResponse()->setHeader('Location', implode('/', [$this->getHttpRequest()
																			   ->getUrl(), $watch->getId()]));

			$this->resource->item = App\Model\DTO\WatchDTO::createFromEntity($watch)->serialize();
			$this->sendResource(self::CONTENT_TYPE);
		}
		catch (App\Model\InvalidArgumentException $e) {
			$this->sendErrorResponse(new Nette\Application\BadRequestException($e->getMessage(), Nette\Http\IResponse::S400_BAD_REQUEST));
		}
	}

	public function actionRead(?int $id)
	{
		if (is_null($id)) {
			$this->readAll();
		}
		else {
			$this->readOne($id);
		}
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
	 *     paramType="query",
	 *     name="page",
	 *     description="Displayed page of the watch list. If the page is out of range, empty array will be returned.",
	 *     required=false,
	 *     type="integer"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="query",
	 *     name="per_page",
	 *     description="Items per page. This parameter works only when 'page' parameter is also included.",
	 *     required=false,
	 *     type="integer"
	 *   ),
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be
	 *                                 expected."),
	 *   @SWG\Response(response="400", description="One or more filter parameters are not in correct format.")
	 * )
	 */
	private function readAll()
	{
		try {
			$query = $this->getHttpRequest()->getQuery();
			$page = $this->getHttpRequest()->getQuery('page', self::DEFAULT_PAGE);
			$pageSize = $this->getHttpRequest()->getQuery('per_page', self::DEFAULT_PAGE_SIZE);

			$data = [];
			foreach ($this->watchRepository->findByFilter($query, $page, $pageSize) as $watch) {
				$data[] = App\Model\DTO\WatchDTO::createFromEntity($watch)->serialize();
			}
			$this->resource->items = $data;
			$this->sendResource(self::CONTENT_TYPE);
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
	 *   @SWG\Parameter(
	 *     paramType="path",
	 *     name="id",
	 *     description="Watch unique identifier.",
	 *     required=true,
	 *     type="integer"
	 *   ),
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be
	 *                                 expected."),
	 *   @SWG\Response(response="404", description="Item with given identifier does not exist.")
	 * )
	 * @param $id
	 *
	 * @return array
	 */
	private function readOne(int $id)
	{
		try {
			$watch = $this->watchRepository->getById($id);
			$this->resource = App\Model\DTO\WatchDTO::createFromEntity($watch)->serialize();
			$this->sendResource(self::CONTENT_TYPE);
		}
		catch (App\Model\Entity\Watch\WatchNotFoundException $e) {
			$this->sendErrorResponse(Drahak\Restful\Application\BadRequestException::notFound('Watch not found'));
		}
	}

	/**
	 * @SWG\Put(path="/watches/{id}",
	 *   tags={"watches"},
	 *   summary="Updates the watch with given id",
	 *   description="Updates the watch with given identifier to data sent in the request body.",
	 *   operationId="updateOneWatch",
	 *   produces={"application/json"},
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="title",
	 *     description="Watch title",
	 *     required=false,
	 *     type="string"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="price",
	 *     description="Watch integer price",
	 *     required=false,
	 *     type="integer"
	 *   ),
	 *   @SWG\Parameter(
	 *     paramType="body",
	 *     name="description",
	 *     description="Watch description",
	 *     required=false,
	 *     type="string"
	 *   ),
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be
	 *                                 expected."),
	 *   @SWG\Response(response="404", description="Item with given identifier does not exist.")
	 * )
	 * @param $id
	 *
	 * @return array
	 */
	public function actionUpdate(int $id)
	{
		try {
			$watch = $this->watchRepository->getById($id);

			$updatedWatchDto = new App\Model\DTO\WatchDTO($this->requestBody);

			$updatedWatch = $this->watchRepository->update($updatedWatchDto, $watch);
			$this->resource->item = App\Model\DTO\WatchDTO::createFromEntity($updatedWatch)->serialize();
			$this->sendResource(self::CONTENT_TYPE);
		}
		catch (App\Model\Entity\Watch\WatchNotFoundException $e) {
			$this->sendErrorResponse(Drahak\Restful\Application\BadRequestException::notFound('Watch not found'));
		}
		catch (App\Model\InvalidArgumentException $e) {
			$this->sendErrorResponse(new Nette\Application\BadRequestException($e->getMessage(), Nette\Http\IResponse::S400_BAD_REQUEST));
		}
	}

	/**
	 * @SWG\Delete(path="/watches/{id}",
	 *   tags={"watches"},
	 *   summary="Deletes watch with given id",
	 *   description="Deletes watch with given identifier.",
	 *   operationId="deleteOneWatch",
	 *   produces={"application/json"},
	 *   @SWG\Parameter(
	 *     paramType="path",
	 *     name="id",
	 *     description="Watch unique identifier.",
	 *     required=true,
	 *     type="integer"
	 *   ),
	 *   @SWG\Response(response="200", description="With valid request provided, this response code can always be
	 *                                 expected."),
	 *   @SWG\Response(response="404", description="Item with given identifier does not exist.")
	 * )
	 * @param $id
	 *
	 * @return array
	 */
	public function actionDelete(int $id)
	{
		try {
			$watch = $this->watchRepository->getById($id);
			$this->watchRepository->delete($watch);
			$this->sendResource(self::CONTENT_TYPE);
		}
		catch (App\Model\Entity\Watch\WatchNotFoundException $e) {
			$this->sendErrorResponse(Drahak\Restful\Application\BadRequestException::notFound('Watch not found'));
		}
	}
}
