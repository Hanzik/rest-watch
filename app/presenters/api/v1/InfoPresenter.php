<?php

namespace App\ApiModule\Presenters;

use Drahak;
use Nette;

final class InfoPresenter extends BaseApiPresenter
{
	/**
	 * @SWG\Get(path="/info",
	 *   tags={"info"},
	 *   summary="Get info about API",
	 *   description="Returns info about API version and resources.",
	 *   operationId="readInfo",
	 *   produces={"application/json"},
	 *   @SWG\Response(response="200", description="This response code can always be expected.")
	 * )
	 */
	public function actionRead()
	{
		$this->resource = [
			'version'   => BaseApiPresenter::VERSION,
			'resources' => [
				'name' => 'watches'
			]
		];
		$this->sendResource(self::CONTENT_TYPE);
	}
}
