<?php

namespace App\Model\Entity\Fountain;

use App;

class FountainService
{
	/**
	 * @param App\Model\DTO\FountainDTO $fountainDTO
	 *
	 * @return Fountain
	 */
	public function create(App\Model\DTO\FountainDTO $fountainDTO)
	{
		$fountain = new Fountain($fountainDTO->getImageBase64(), $fountainDTO->getColor(), $fountainDTO->getHeight());

		return $fountain;
	}

}
