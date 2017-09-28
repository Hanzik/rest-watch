<?php

namespace App\Model\Entity\Watch;

use App\Model\Entity;

class WatchService
{
	public function create(string $title, int $price, string $description, Entity\Fountain\Fountain $fountain)
	{
		return new Watch($title, $price, $description, $fountain);
	}
}
