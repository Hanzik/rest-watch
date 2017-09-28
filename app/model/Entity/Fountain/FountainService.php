<?php

namespace App\Model\Entity\Fountain;

class FountainService
{
	/**
	 * @param null|string $imageBase64
	 * @param null|string $color
	 * @param null|string $height
	 * @return Fountain
	 */
	public function create(?string $imageBase64, ?string $color, ?string $height)
	{
		$fountain = new Fountain($imageBase64, $color, $height);

		return $fountain;
	}

}
