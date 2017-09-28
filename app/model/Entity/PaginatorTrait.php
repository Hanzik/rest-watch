<?php

namespace App\Model\Entity;

use Doctrine;

trait PaginationTrait
{
	private function setPage(Doctrine\ORM\QueryBuilder &$query, ?int $page, ?int $perPage)
	{
		if (is_null($page) && is_null($perPage)) {
			return;
		}

		$query->setFirstResult($page * $perPage + 1)
			  ->setMaxResults($perPage);
	}
}
