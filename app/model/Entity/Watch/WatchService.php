<?php

namespace App\Model\Entity\Watch;

use App;
use App\Model\Entity;

class WatchService
{
	/**
	 * @param App\Model\DTO\WatchDTO   $watchDTO
	 * @param Entity\Fountain\Fountain $fountain
	 *
	 * @return Watch
	 */
	public function create(App\Model\DTO\WatchDTO $watchDTO, Entity\Fountain\Fountain $fountain)
	{
		return new Watch($watchDTO->getTitle(), $watchDTO->getPrice(), $watchDTO->getDescription(), $fountain);
	}

	/**
	 * @param App\Model\DTO\WatchDTO $watchDTO  source of the new data
	 * @param Watch                  $watch 	original watch to be updated
	 *
	 * @return Watch
	 */
	public function update(App\Model\DTO\WatchDTO $watchDTO, Watch $watch)
	{
		$watch->setDateModified();

		if (!is_null($watchDTO->getTitle())) {
			$watch->setTitle($watchDTO->getTitle());
		}
		if (!is_null($watchDTO->getPrice())) {
			$watch->setPrice($watchDTO->getPrice());
		}
		if (!is_null($watchDTO->getDescription())) {
			$watch->setDescription($watchDTO->getDescription());
		}

		return $watch;
	}
}
